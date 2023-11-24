<?php
declare(strict_types=1);

namespace App\Model\OAuth2;

use League\OAuth2\Server\Entities\UserEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserModel implements UserEntityInterface, UserInterface
{

    public function __construct(readonly string $identifier, readonly array $roles)
    {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return $this->getIdentifier();
    }
}