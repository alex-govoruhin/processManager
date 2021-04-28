<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\Service;


class Config
{
    private $name = '';
    private $instanceName = '';
    private $commands;

    public function __construct(string $name, string $instanceName, array $commands)
    {
        $this->name = $name;
        $this->instanceName = $instanceName;
        $this->commands = $commands;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getInstanceName(): string
    {
        return $this->instanceName;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
}