<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ScopeTrait
{
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $scopes = null;

    public function getScopes(): ?string
    {
        return $this->scopes;
    }

    public function setScopes(?string $scopes): void
    {
        $this->scopes = $scopes;
    }
}