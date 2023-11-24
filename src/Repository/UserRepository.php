<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserById(string $id): ?User
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getUser(string $username, string $password): ?User
    {
        $user = $this->findOneBy(['username' => $username]);
        if (is_null($user)) {
            return null;
        }

        if (password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }
}
