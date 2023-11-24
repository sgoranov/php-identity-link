<?php
declare(strict_types=1);

namespace App\Model\OAuth2;

use League\OAuth2\Server\Entities\ClientEntityInterface;

class ClientModel implements ClientEntityInterface
{
    private string $identifier;
    private string $name;
    private string $redirectUri;
    private bool $isConfidential;
    private array $scopes;
    private array $grantTypes;

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    public function isConfidential(): bool
    {
        return $this->isConfidential;
    }

    public function setIsConfidential(bool $isConfidential): void
    {
        $this->isConfidential = $isConfidential;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    public function getGrantTypes(): array
    {
        return $this->grantTypes;
    }

    public function setGrantTypes(array $grantTypes): void
    {
        $this->grantTypes = $grantTypes;
    }

    public static function getInstanceByDoctrineEntity(\App\Entity\Client $entity): ClientModel
    {
        return ClientMapper::getInstanceByDoctrineEntity($entity);
    }


}