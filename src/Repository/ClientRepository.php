<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientGrantType;
use App\Entity\ClientSecret;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.identifier = :val')
            ->setParameter('val', $clientIdentifier)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.isConfidential = :isConfidential')
            ->innerJoin(ClientGrantType::class, 'gt', Join::WITH, 'gt.client = t.id')
            ->andWhere('gt.grantType = :grantType')
            ->setParameter('grantType', $grantType)
        ;

        if ($clientSecret !== null) {
            $result = $queryBuilder->select('s')
                ->setParameter('isConfidential', true)
                ->innerJoin(ClientSecret::class, 's', Join::WITH, 's.client = t.id')
                ->andWhere('s.expiryDateTime >= :now')
                ->setParameter('now', new DateTime())
                ->getQuery()
                ->getResult()
            ;

            /** @var ClientSecret $secret */
            foreach ($result as $secret) {
                if (password_verify($clientSecret, $secret->getSecret())) {
                    return true;
                }
            }

        } else {
            $result = $queryBuilder->select('t')
                ->setParameter('isConfidential', false)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            if ($result !== null) {
                return true;
            }
        }

        return false;
    }
}
