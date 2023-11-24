<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientSecret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientSecret>
 *
 * @method ClientSecret|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientSecret|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientSecret[]    findAll()
 * @method ClientSecret[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientSecretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientSecret::class);
    }

    public function validateClientSecret(string $clientIdentifier, string $clientSecret): bool
    {
        // check client secret
        $result = $this->createQueryBuilder('t')
            ->select('t')
            ->innerJoin(Client::class, 'c', Join::WITH, 't.client = c.id')
            ->andWhere('t.expiryDateTime >= :now')
            ->andWhere('c.identifier = :identifier')
            ->setParameter('now', new \DateTime())
            ->setParameter('identifier', $clientIdentifier)
            ->getQuery()
            ->getResult()
        ;

        /** @var ClientSecret $secret */
        foreach ($result as $secret) {

            if (password_verify($clientSecret, $secret->getSecret())) {
                return true;
            }
        }

        return false;
    }
}
