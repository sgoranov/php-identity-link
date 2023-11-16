<?php

namespace App\Entity;

use App\Repository\AccessTokenRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private ?string $id = null;

    #[ORM\Column]
    private string $identifier;

    #[ORM\Column(type: "uuid")]
    private string $userIdentifier;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $client;

    #[ORM\JoinTable(name: 'access_tokens_scopes')]
    #[ORM\JoinColumn(name: 'access_token_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'scope_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Scope::class, cascade: ["remove", "persist"])]
    private Collection $scopes;

    #[ORM\Column]
    private DateTimeImmutable $expiryDateTime;

    #[ORM\Column]
    private bool $isRevoked = false;

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

    public function getExpiryDateTime()
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

    public function addScope(ScopeEntityInterface $scope): void
    {
        $this->scopes->add($scope);
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function isRevoked(): bool
    {
        return $this->isRevoked;
    }

    public function setIsRevoked(bool $isRevoked): void
    {
        $this->isRevoked = $isRevoked;
    }
}
