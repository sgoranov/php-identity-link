<?php
declare(strict_types=1);

namespace App\Service\OAuth2;


use App\Entity\AccessToken;
use App\Model\OAuth2\AccessTokenModel;
use App\Model\OAuth2\ScopeModel;
use App\ModelMapper\AccessTokenMapper;
use App\Repository\AccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenService implements AccessTokenRepositoryInterface
{
    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly AccessTokenMapper $accessTokenMapper,
    )
    {
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessTokenModel
    {
        $token = new AccessTokenModel();
        $token->setClient($clientEntity);
        $token->setUserIdentifier($userIdentifier);
        $token->setScopes($scopes);
        $token->setIsRevoked(false);

        return $token;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->entityManager->persist($this->accessTokenMapper->toEntity($accessTokenEntity));
        $this->entityManager->flush();
    }

    public function revokeAccessToken($tokenId)
    {
        /** @var AccessTokenRepository $repository */
        $repository = $this->entityManager->getRepository(AccessToken::class);
        $entity = $repository->getByIdentifier($tokenId);
        $entity->setIsRevoked(true);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        /** @var AccessTokenRepository $repository */
        $repository = $this->entityManager->getRepository(AccessToken::class);
        $entity = $repository->getByIdentifier($tokenId);

        return $entity->isRevoked();
    }
}