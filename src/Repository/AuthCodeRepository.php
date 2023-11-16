<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\AuthCode;
use App\Repository\Trait\RevocationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

/**
 * @extends ServiceEntityRepository<AuthCode>
 *
 * @method AuthCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthCode[]    findAll()
 * @method AuthCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthCodeRepository extends ServiceEntityRepository implements AuthCodeRepositoryInterface
{
    use RevocationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthCode::class);
    }

    public function getNewAuthCode(): AuthCode
    {
        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($authCodeEntity);
        $entityManager->flush();
    }

    public function revokeAuthCode($codeId)
    {
        $this->revoke($codeId);
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        return $this->isRevoked($codeId);
    }
}
