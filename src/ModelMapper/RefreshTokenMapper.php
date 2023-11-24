<?php
declare(strict_types=1);

namespace App\ModelMapper;

use App\Entity\RefreshToken;
use App\Model\OAuth2\RefreshTokenModel;
use App\Repository\AccessTokenRepository;

class RefreshTokenMapper
{
    public function __construct(
        readonly AccessTokenMapper $accessTokenMapper,
        readonly AccessTokenRepository $accessTokenRepository,
    )
    {
    }

    public function toEntity(RefreshTokenModel $model): RefreshToken
    {
        $accessTokenEntity = $this->accessTokenRepository->findOneBy(['identifier' => $model->getAccessToken()->getIdentifier()]);
        if ($accessTokenEntity === null) {
            $accessTokenEntity = $this->accessTokenMapper->toEntity($model->getAccessToken());
        }

        $entity = new RefreshToken();
        $entity->setIsRevoked($model->isRevoked());
        $entity->setIdentifier($model->getIdentifier());
        $entity->setExpiryDateTime($model->getExpiryDateTime());
        $entity->setAccessToken($accessTokenEntity);

        return $entity;
    }

    public function toModel(RefreshToken $entity): RefreshTokenModel
    {
        $model = new RefreshTokenModel();
        $model->setIsRevoked($entity->isRevoked());
        $model->setIdentifier($entity->getIdentifier());
        $model->setExpiryDateTime($entity->getExpiryDateTime());
        $model->setAccessToken($this->accessTokenMapper->toModel($entity->getAccessToken()));

        return $model;
    }
}