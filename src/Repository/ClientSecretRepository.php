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
}
