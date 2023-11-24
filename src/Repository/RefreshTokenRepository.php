<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\AccessToken;
use App\Entity\RefreshToken;
use App\Repository\Trait\RevocationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshTokenRepository extends ServiceEntityRepository
{
    use RevocationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    public function getByIdentifier(string $tokenId): RefreshToken
    {
        $result = $this->findOneBy(['identifier' => $tokenId]);

        if ($result === null) {
            throw new \Exception('Token not found');
        }

        return $result;
    }
}
