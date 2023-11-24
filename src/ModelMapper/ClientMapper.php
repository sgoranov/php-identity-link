<?php
declare(strict_types=1);

namespace App\ModelMapper;

use App\Entity\Client;
use App\Model\OAuth2\ClientModel;
use App\Model\OAuth2\GrantTypeModel;
use App\Model\OAuth2\ScopeModel;

class ClientMapper
{
    public function toEntity(ClientModel $model): Client
    {
        $entity = new Client();
        $entity->setIdentifier($model->getIdentifier());
        $entity->setScopes(json_encode($model->getScopes()));
        $entity->setRedirectUri($model->getRedirectUri());
        $entity->setName($model->getName());
        $entity->setIsConfidential($model->isConfidential());
        $entity->setGrantTypes(json_encode($model->getGrantTypes()));

        return $entity;
    }

    public function toModel(Client $entity): ClientModel
    {
        $model = new ClientModel();
        $model->setIdentifier($entity->getIdentifier());
        $model->setScopes(ScopeModel::convertToObjectsArray($entity->getScopes()));
        $model->setRedirectUri($entity->getRedirectUri());
        $model->setName($entity->getName());
        $model->setIsConfidential($entity->isConfidential());
        $model->setGrantTypes(GrantTypeModel::convertToObjectsArray($entity->getGrantTypes()));

        return $model;
    }
}