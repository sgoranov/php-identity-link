<?php

namespace App\Repository;

use App\Entity\AccessToken;
use App\Repository\Trait\RevocationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * @extends ServiceEntityRepository<AccessToken>
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends ServiceEntityRepository implements AccessTokenRepositoryInterface
{
    use RevocationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessToken
    {
        $token = new AccessToken();
        $token->setClient($clientEntity);
        $token->setUserIdentifier($userIdentifier);
        $token->setScopes($scopes);
        $token->setIsRevoked(false);

        return $token;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($accessTokenEntity);
        $entityManager->flush();
    }

    public function revokeAccessToken($tokenId)
    {
        $this->revoke($tokenId);
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        return $this->isRevoked($tokenId);
    }
}
