<?php

namespace App\Repository;

use App\Entity\AccessToken;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
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
        $token = $this->findOneBy(['identifier' => $tokenId]);
        if ($token === null) {
            throw new \InvalidArgumentException('Invalid token identifier passed.');
        }

        $token->setIsRevoked(true);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($token);
        $entityManager->flush();
    }

    public function isAccessTokenRevoked($tokenId)
    {
        $token = $this->findOneBy(['identifier' => $tokenId]);
        if ($token === null) {
            throw new \InvalidArgumentException('Invalid token identifier passed.');
        }

        return $token->isRevoked();
    }
}
