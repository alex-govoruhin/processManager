<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\Service;

trait TerminateOnSignalTrait
{
    /**
     * @var bool
     */
    private $signalTriggered = false;

    /**
     * Call this function at execute() method in command (not constructor!) for enabling signal triggering
     */
    private function enableSignalHandling(): void
    {
        // PHP 7.1 allows async signals
        pcntl_async_signals(true);

        // Termination ('kill' was called)
        pcntl_signal(SIGTERM, function () {
            $this->signalTriggered = true;
        });

        // Interrupted (Ctrl-C is pressed)
        pcntl_signal(SIGINT, function () {
            $this->signalTriggered = true;
        });
    }

    /**
     * Call this function to check signal and terminate the process
     *
     * @param callable|null $callback Actions tha must be executed before exit
     */
    private function terminateOnSignal(callable $callback = null): void
    {
        if ($this->signalTriggered) {
            if (null !== $callback) {
                $callback();
            }

            exit('Terminated after triggering SIGTERM or SIGINT signal');
        }
    }
}
