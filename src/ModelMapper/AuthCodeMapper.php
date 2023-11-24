<?php
declare(strict_types=1);

namespace App\ModelMapper;

use App\Entity\AuthCode;
use App\Model\OAuth2\AuthCodeModel;

class AuthCodeMapper
{

    public function toEntity(AuthCodeModel $model): AuthCode
    {
        $entity = new AuthCode();
        $entity->setIdentifier($model->getIdentifier());
        $entity->setScopes(json_encode($model->getScopes()));
        $entity->setExpiryDateTime($model->getExpiryDateTime());
        $entity->setRedirectUri($model->getRedirectUri());
        $entity->setUserIdentifier($model->getUserIdentifier());
        $entity->setClientIdentifier($model->getClient()->getIdentifier());
        $entity->setIsRevoked($model->isRevoked());

        return $entity;
    }
}