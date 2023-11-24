<?php
declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\User;
use App\ModelMapper\UserMapper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserService implements UserRepositoryInterface
{

    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly UserMapper $userMapper,
    )
    {
    }

    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->getUser($username, $password);

        if ($user == null) {

            return null;
        }

        return $this->userMapper->toModel($user);
    }
}