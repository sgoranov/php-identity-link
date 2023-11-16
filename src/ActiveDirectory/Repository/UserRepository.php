<?php
declare(strict_types=1);

namespace App\ActiveDirectory\Repository;

use App\ActiveDirectory\Entity\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity): ?User
    {
        // TODO: Implement getUserEntityByUserCredentials() method.

        return null;
    }
}