<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Ecredit\ProcessManagerBundle\Entity\Supervisor;
use Ecredit\ProcessManagerBundle\Repository\SupervisorRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessManager
{
    //use TerminateOnSignalTrait;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $pool = [];
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var SupervisorRepository
     */
    private $supervisorRepository;

    /**
     * ProcessManager constructor.
     * @param Config $config
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Config $config, EntityManagerInterface $entityManager)
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->supervisorRepository = $this->entityManager->getRepository(Supervisor::class);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return OutputInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): OutputInterface
    {
        $output->writeln("{$this->config->getName()} Starting supervisor");
        $this->runCommands($output);
        $output->writeln("{$this->config->getName()} Finished supervisor");

        return $output;
    }

    /**
     * @param OutputInterface $output
     */
    private function runCommands(OutputInterface $output): void
    {
        $this->createPool();
        $this->startPool($output);
    }

    private function createPool(): void
    {
        foreach ($this->config->getCommands() as $item) {
            $command = $item['command'];
            $threads = $item['threads'];

            $key = md5($command);

            $this->pool[$key] = ['proc' => [], 'command' => $command, 'threads' => $threads];

            for ($i = 0; $i < $threads; $i++) {
                $this->pool[$key]['proc'][$i] = false;
            }
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function startPool(OutputInterface $output): void
    {
        while (true) {
            //$this->enableSignalHandling();
            foreach ($this->pool as &$item) {
                for ($i = 0; $i < $item['threads']; $i++) {
                    if (false === $item['proc'][$i]) {
                        $this->procOpen($item, $i, $output);
                    } else {
                        $proc = proc_get_status($item['proc'][$i]);
                        if (false === $proc['running']  ) {
                            $this->procClose($item, $i, $output);
                        }
                    }
                }

                sleep(1);
            }
            //$this->checkSignal($output);
        }
    }

    private function procOpen(array &$item, int $k, OutputInterface $output): void
    {
        $item['proc'][$k] = proc_open($item['command'], [], $foo);
        $this->procSaveDb($item, $k);
        $output->writeln("{$this->config->getName()}[ProcessManager] Starting command {$item['command']}\n");
    }

    private function procClose(array &$item, int $k, OutputInterface $output): void
    {
        proc_close($item['proc'][$k]);
        $proc = proc_get_status($item['proc'][$k]);

        $command = $this->supervisorRepository->findBy(["pid" => $proc['pid']]);
        $this->entityManager->remove($command);
        $this->entityManager->flush();

        $item['proc'][$k] = proc_open($item['command'], [], $foo);
        $this->procSaveDb($item, $k);
        $output->writeln("{$this->config->getName()}[ProcessManager] Starting command {$item['command']}\n");
    }

    private function procSaveDb(array $item, int $key): void
    {
        $proc = proc_get_status($item['proc'][$key]);

        $command = new Supervisor();
        $command->setPid($proc['pid']);

        $status = '';
        if (true === $proc['running'])
            $status = "running";

        if (true === $proc['stopped'])
            $status = "stopped";

        $command->setStatus($status);
        $command->setCommand($item['command']);
        $command->setStartTime(new DateTime());

        $this->entityManager->persist($command);
        $this->entityManager->flush();
    }

    /**
     * @return array
     */
    public function procStatus(): array
    {
        return $this->supervisorRepository->findAll();
    }

    private function checkSignal(OutputInterface $output): void
    {
        $this->terminateOnSignal(
            function () use ($output) {
                foreach ($this->pool as &$item) {
                    for ($i = 0; $i < $item['threads']; $i++) {
                        $this->procClose($item, $i, $output);
                    }
                }
            }
        );
    }
}