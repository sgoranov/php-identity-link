<?php
declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\RefreshToken;
use App\Model\OAuth2\RefreshTokenModel;
use App\ModelMapper\RefreshTokenMapper;
use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenService implements RefreshTokenRepositoryInterface
{
    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly RefreshTokenMapper $refreshTokenMapper,
    )
    {
    }

    public function getNewRefreshToken(): RefreshTokenModel
    {
        $token = new RefreshTokenModel();
        $token->setIsRevoked(false);

        return $token;
    }

    /**
     * @param RefreshTokenModel $refreshTokenEntity
     * @return void
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->entityManager->persist($this->refreshTokenMapper->toEntity($refreshTokenEntity));
        $this->entityManager->flush();
    }

    public function revokeRefreshToken($tokenId)
    {
        /** @var RefreshTokenRepository $repository */
        $repository = $this->entityManager->getRepository(RefreshToken::class);
        $entity = $repository->getByIdentifier($tokenId);
        $entity->setIsRevoked(true);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        /** @var RefreshTokenRepository $repository */
        $repository = $this->entityManager->getRepository(RefreshToken::class);
        $entity = $repository->getByIdentifier($tokenId);

        return $entity->isRevoked();
    }
}