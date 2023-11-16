<?php

namespace App\Entity;

use App\Entity\Traits\RevocationTrait;
use App\Entity\Traits\ScopeTrait;
use App\Repository\AuthCodeRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;

#[ORM\Entity(repositoryClass: AuthCodeRepository::class)]
class AuthCode implements AuthCodeEntityInterface
{
    use AuthCodeTrait, ScopeTrait, RevocationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private ?string $id = null;

    #[ORM\Column(type: "text")]
    private string $identifier;

    #[ORM\Column]
    private string $userIdentifier;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $client;

    #[ORM\Column]
    private DateTimeImmutable $expiryDateTime;

    #[ORM\Column(type: "string", length: 500)]
    protected $redirectUri;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getExpiryDateTime(): DateTimeImmutable
    {
        return $this->expiryDateTime;
    }

    public function setExpiryDateTime(DateTimeImmutable $dateTime): void
    {
        $this->expiryDateTime = $dateTime;
    }

    public function setUserIdentifier($identifier): void
    {
        $this->userIdentifier = $identifier;

    }

    public function getUserIdentifier(): ?string
    {
        return $this->userIdentifier;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(ClientEntityInterface $client): void
    {
        $this->client = $client;
    }
}
