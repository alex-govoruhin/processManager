<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\Controller;

use Ecredit\ProcessManagerBundle\Entity\Supervisor;
use Ecredit\ProcessManagerBundle\Service\ProcessManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class SupervisorController
{
    /**
     * @var ProcessManager
     */
    private ProcessManager $processManager;

    public function __construct(ProcessManager $processManager)
    {
        $this->processManager = $processManager;
    }

    public function health(): JsonResponse
    {
        $procStatus = $this->processManager->procStatus();
        return new JsonResponse(array_map(fn(Supervisor $item) => $item->toArray(), $procStatus));
    }
}