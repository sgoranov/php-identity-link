<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\ScopeTrait;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    use ScopeTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private string $id;

    #[ORM\Column(length: 100)]
    private string $identifier;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 500)]
    private ?string $redirectUri = null;

    #[ORM\Column]
    private bool $isConfidential = true;

    #[ORM\Column(type: "text")]
    private string $grantTypes;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri(?string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    public function setIsConfidential(?bool $isConfidential): void
    {
        $this->isConfidential = $isConfidential;
    }

    public function isConfidential(): ?bool
    {
        return $this->isConfidential;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGrantTypes(): string
    {
        return $this->grantTypes;
    }

    public function setGrantTypes(string $grantTypes): void
    {
        $this->grantTypes = $grantTypes;
    }
}
