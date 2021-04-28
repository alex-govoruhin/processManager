<?php

declare(strict_types=1);

namespace Ecredit\ProcessManagerBundle\Entity;

use DateTime;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="Ecredit\ProcessManagerBundle\Repository\SupervisorRepository")
 * @ORM\Table(name="supervisor")
 */
class Supervisor
{
    /**
     * @var string $id
     *
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * @ORM\Id
     *
     */
    protected string $id;

    /**
     * @var int $pid
     *
     * @ORM\Column(type="integer")
     */
    protected int $pid;

    /**
     * @var string $status
     *
     * @ORM\Column(type="string")
     *
     */
    protected string $status;

    /**
     * @var string $command
     *
     * @ORM\Column(type="string")
     *
     */
    protected string $command;

    /**
     * @var DateTime $startTime
     *
     * @ORM\Column(type="datetime")
     */
    protected DateTime $startTime;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid(int $pid): void
    {
        $this->pid = $pid;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return DateTime
     */
    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    /**
     * @param DateTime $startTime
     */
    public function setStartTime(DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function toArray() {
        return get_object_vars($this);
    }

}