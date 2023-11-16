<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements ClientEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: UuidV4::class)]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 500)]
    private ?string $redirectUri = null;

    #[ORM\Column]
    private ?bool $isConfidential = true;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdentifier()
    {
        return $this->getId();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
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
}
