<?php

declare(strict_types=1);

namespace Ecredit\ProcessManager\Command;

use Ecredit\LoggingBundle\Service\ProcessManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProcessManagerCommand
 */
class ProcessManagerCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:supervise';

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
        $this->processManager->execute($input, $output);

        return $output;
    }

    protected function configure()
    {
        $this->setName('app:supervise')
            ->setAliases(['app:supervise'])
            ->setDescription('Process Manager');
    }
}
