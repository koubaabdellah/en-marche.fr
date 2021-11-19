<?php

namespace App\Entity\Pap;

use App\Entity\Adherent;
use Doctrine\ORM\Mapping as ORM;

trait StatusTrait
{
    /**
     * @ORM\Column(length=25)
     */
    private string $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $closedAt = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Adherent")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Adherent $closedBy;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getClosedAt(): \DateTimeInterface
    {
        return $this->closedAt;
    }

    public function setClosedAt(\DateTimeInterface $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    public function getClosedBy(): ?Adherent
    {
        return $this->closedBy;
    }

    public function setClosedBy(?Adherent $closedBy): void
    {
        $this->closedBy = $closedBy;
    }
}
