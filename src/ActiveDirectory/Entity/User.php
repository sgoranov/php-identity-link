<?php
declare(strict_types=1);

namespace App\ActiveDirectory\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;

class User implements UserEntityInterface
{

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return null;
    }
}