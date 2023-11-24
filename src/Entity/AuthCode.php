<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\RevocationTrait;
use App\Entity\Traits\ScopeTrait;
use App\Repository\AuthCodeRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthCodeRepository::class)]
class AuthCode
{
    use ScopeTrait, RevocationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private ?string $id = null;

    #[ORM\Column(type: "text")]
    private string $identifier;

    #[ORM\Column]
    private string $userIdentifier;

    #[ORM\Column]
    private string $clientIdentifier;

    #[ORM\Column]
    private DateTimeImmutable $expiryDateTime;

    #[ORM\Column(type: "string", length: 500, nullable: true)]
    protected ?string $redirectUri = null;

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

    public function getClientIdentifier(): string
    {
        return $this->clientIdentifier;
    }

    public function setClientIdentifier(string $clientIdentifier): void
    {
        $this->clientIdentifier = $clientIdentifier;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri($uri): void
    {
        $this->redirectUri = $uri;
    }
}
