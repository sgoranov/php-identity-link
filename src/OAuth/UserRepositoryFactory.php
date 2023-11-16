<?php

namespace App\OAuth;

use App\ActiveDirectory\Repository\UserRepository as AdUserRepository;
use App\Repository\UserRepository as DbUserRepository;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepositoryFactory
{
    private bool $isActiveDirectoryEnabled;

    public function __construct(
        readonly DbUserRepository $dbUserRepository,
        readonly AdUserRepository $adUserRepository
    )
    {
    }

    public function setIsActiveDirectoryEnabled(bool $isActiveDirectoryEnabled): void
    {
        $this->isActiveDirectoryEnabled = $isActiveDirectoryEnabled;
    }

    public function create(): UserRepositoryInterface
    {
        if ($this->isActiveDirectoryEnabled) {

            return $this->adUserRepository;
        }

        return $this->dbUserRepository;
    }
}