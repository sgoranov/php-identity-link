<?php

namespace App\Entity;

use App\Repository\ScopeRepository;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: ScopeRepository::class)]
class Scope implements ScopeEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: UuidV4::class)]
    private ?string $id = null;

    #[ORM\Column(unique: true)]
    private string $identifier;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function jsonSerialize(): ?string
    {
        return $this->getIdentifier();
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }
}
