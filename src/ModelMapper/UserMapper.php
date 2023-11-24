<?php
declare(strict_types=1);

namespace App\ModelMapper;

use App\Entity\User;
use App\Model\OAuth2\UserModel;

class UserMapper
{

    public function toModel(User $entity): UserModel
    {
        $model = new UserModel($entity->getIdentifier(), []);

        return $model;
    }
}