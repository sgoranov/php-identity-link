<?php

namespace App\Repository;

use App\Entity\Client;
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

    public function validateClient($clientIdentifier, $clientSecret = null, $grantType = null): bool
    {
//        ->innerJoin(Image::class, 'c', Join::WITH,
//        'a.id = c.category AND c.isPublic = :isPublic
//                AND (c.expirationDateTime IS NULL OR c.expirationDateTime >= :today)')


        // TODO: Handle grant types

        if ($clientSecret !== null) {

        }


        $result = $this->createQueryBuilder('t')
            ->select('s')
            ->innerJoin(ClientSecret::class, 's', Join::WITH, 's.client = t.id')
            ->andWhere('t.isConfidential = :isConfidential')
            ->setParameter('isConfidential', true)
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

        return false;
    }
}
