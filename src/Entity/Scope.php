<?php

namespace App\Entity;

use App\Repository\ScopeRepository;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

#[ORM\Entity(repositoryClass: ScopeRepository::class)]
class Scope implements ScopeEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
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
