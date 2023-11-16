<?php

namespace App\Repository;

use App\Entity\RefreshToken;
use App\Repository\Trait\RevocationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    use RevocationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    public function getNewRefreshToken(): RefreshToken
    {
        $token = new RefreshToken();
        $token->setIsRevoked(false);

        return $token;
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($refreshTokenEntity);
        $entityManager->flush();
    }

    public function revokeRefreshToken($tokenId)
    {
        $this->revoke($tokenId);
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        return $this->isRevoked($tokenId);
    }
}
