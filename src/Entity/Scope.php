<?php

namespace App\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

class Scope implements ScopeEntityInterface
{
    const SCOPE_OPENID = 'openid';
    const SCOPE_PROFILE = 'profile';
    const SCOPE_GROUPS = 'groups';

    private string $identifier;

    public function __construct(string $identifier)
    {
        if (!in_array($identifier, [
            self::SCOPE_OPENID,
            self::SCOPE_PROFILE,
            self::SCOPE_GROUPS,
        ])) {
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
}
