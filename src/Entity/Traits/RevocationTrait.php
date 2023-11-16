<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait RevocationTrait
{
    #[ORM\Column]
    private bool $isRevoked = false;

    public function isRevoked(): bool
    {
        return $this->isRevoked;
    }

    public function setIsRevoked(bool $isRevoked): void
    {
        $this->isRevoked = $isRevoked;
    }
}