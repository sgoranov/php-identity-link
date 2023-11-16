<?php

namespace App\Repository;

use App\Entity\ClientSecret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

//    /**
//     * @return ClientSecret[] Returns an array of ClientSecret objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClientSecret
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
