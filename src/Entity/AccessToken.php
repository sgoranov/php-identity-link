<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\RevocationTrait;
use App\Entity\Traits\ScopeTrait;
use App\Repository\AccessTokenRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait, ScopeTrait, RevocationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private string $id;

    #[ORM\Column(type: "text")]
    private string $identifier;

    #[ORM\Column(nullable: true)]
    private ?string $userIdentifier;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $client;

    #[ORM\Column]
    private DateTimeImmutable $expiryDateTime;

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
