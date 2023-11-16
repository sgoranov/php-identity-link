<?php

namespace App\Repository;

use App\Entity\ClientGrantType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientGrantType>
 *
 * @method ClientGrantType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientGrantType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientGrantType[]    findAll()
 * @method ClientGrantType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientGrantTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientGrantType::class);
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
