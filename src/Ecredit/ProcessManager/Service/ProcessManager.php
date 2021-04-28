<?php

declare(strict_types=1);

namespace Ecredit\ProcessManager\Service;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessManager
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $pool = [];

    /**
     * ProcessManager constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
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
            foreach ($this->pool as &$item) {
                for ($i = 0; $i < $item['threads']; $i++) {
                    if (false === $item['proc'][$i]) {
                        $item['proc'][$i] = proc_open($item['command'], [], $foo);
                        $output->writeln("{$this->config->getName()}[ProcessManager] Starting command {$item['command']}\n");
                    } else {
                        $proc = proc_get_status($item['proc'][$i]);
                        if (false === $proc['running']  ) {
                            proc_close($item['proc'][$i]);
                            $item['proc'][$i] = proc_open($item['command'], [], $foo);
                            $output->writeln("{$this->config->getName()}[ProcessManager] Starting command {$item['command']}\n");
                        }
                    }
                }

                sleep(1);
            }
        }
    }
}