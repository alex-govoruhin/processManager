<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\Command;

use Ecredit\ProcessManagerBundle\Entity\Supervisor;
use Ecredit\ProcessManagerBundle\Service\ProcessManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ProcessManagerCommand
 */
class ProcessManagerHealthCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'ecredit:supervisor:health';

    /**
     * @var ProcessManager
     */
    private $processManager;

    /**
     * ProcessManagerCommand constructor.
     * @param ProcessManager $processManager
     */
    public function __construct(ProcessManager $processManager)
    {
        $this->processManager = $processManager;
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|OutputInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Supervisor[]|array $health */
        $health = $this->processManager->procStatus();
        $tableResult = [];
        foreach ($health as $item) {
            $tableResult[] = [
                $item->getId(),
                $item->getPid(),
                $item->getStatus(),
                $item->getCommand(),
                $item->getStartTime()->format('Y-m-d H:i:s'),
            ];
        }
        $io = new SymfonyStyle($input, $output);
        $io->title('Process Manager: status');
        $io->table(
            array('id', 'pid', 'status', 'command', 'star_time'),
            $tableResult
        );
        return 0;
    }

    protected function configure()
    {
        $this->setName('ecredit:supervisor:health')
            ->setAliases(['ecredit:supervisor:health'])
            ->setDescription('Process Manager');
    }
}
