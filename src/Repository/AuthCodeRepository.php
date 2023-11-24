<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\AuthCode;
use App\Repository\Trait\RevocationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuthCode>
 *
 * @method AuthCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthCode[]    findAll()
 * @method AuthCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthCodeRepository extends ServiceEntityRepository
{
    use RevocationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthCode::class);
    }

    public function getByIdentifier(string $codeId): AuthCode
    {
        $result = $this->findOneBy(['identifier' => $codeId]);

        if ($result === null) {
            throw new \Exception('Code not found');
        }

        return $result;
    }
}
