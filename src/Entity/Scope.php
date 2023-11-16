<?php
declare(strict_types=1);

namespace App\Entity;

use App\OAuth\Scopes;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

class Scope implements ScopeEntityInterface
{
    private string $identifier;

    public function __construct(string $identifier)
    {
        if (!in_array($identifier, Scopes::getSupportedAsString(), true)) {
            throw new \InvalidArgumentException(sprintf('Invalid scope %s passed.', $identifier));
        }

        $this->identifier = $identifier;
    }

    public function jsonSerialize(): ?string
    {
        return $this->identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function __toString(): string
    {
        return $this->identifier;
    }
}
