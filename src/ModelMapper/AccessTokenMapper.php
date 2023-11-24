<?php
declare(strict_types=1);

namespace App\ModelMapper;

use App\Entity\AccessToken;
use App\Model\OAuth2\AccessTokenModel;
use App\Model\OAuth2\ScopeModel;
use App\Repository\ClientRepository;

class AccessTokenMapper
{
    public function __construct(
        readonly ClientRepository $clientRepository,
        readonly ClientMapper $clientMapper,
    )
    {
    }

    public function toEntity(AccessTokenModel $model): AccessToken
    {
        $entity = new AccessToken();
        $entity->setScopes(json_encode($model->getScopes()));
        $entity->setIsRevoked($model->isRevoked());
        $entity->setIdentifier($model->getIdentifier());
        $entity->setExpiryDateTime($model->getExpiryDateTime());
        $entity->setClientIdentifier($model->getClient()->getIdentifier());
        $entity->setUserIdentifier($model->getUserIdentifier());

        return $entity;
    }

    public function toModel(AccessToken $entity): AccessTokenModel
    {
        $model = new AccessTokenModel();
        $model->setScopes(ScopeModel::convertToObjectsArray($entity->getScopes()));
        $model->setIsRevoked($entity->isRevoked());
        $model->setIdentifier($entity->getIdentifier());
        $model->setExpiryDateTime($entity->getExpiryDateTime());
        $model->setClient($this->clientMapper->toModel(
            $this->clientRepository->getByIdentifier($entity->getClientIdentifier())
        ));
        $model->setUserIdentifier($entity->getUserIdentifier());

        return $model;
    }
}