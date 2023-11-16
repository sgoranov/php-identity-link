<?php

namespace App\Repository;

use App\Entity\ClientGrantTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientGrantTypes>
 *
 * @method ClientGrantTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientGrantTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientGrantTypes[]    findAll()
 * @method ClientGrantTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientGrantTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientGrantTypes::class);
    }

//    /**
//     * @return ClientGrantTypes[] Returns an array of ClientGrantTypes objects
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

//    public function findOneBySomeField($value): ?ClientGrantTypes
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
