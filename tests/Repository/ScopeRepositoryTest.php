<?php

namespace App\Tests\Repository;

use App\Entity\Client;
use App\Entity\Scope;
use App\OAuth\GrantTypes;
use App\OAuth\Scopes;
use App\Repository\ClientRepository;
use App\Repository\ScopeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ScopeRepositoryTest extends KernelTestCase
{
    private static ClientRepository $clientRepository;
    private static ScopeRepository $scopeRepository;

    public static function setUpBeforeClass(): void
    {
        $container = static::getContainer();
        self::$clientRepository = $container->get(ClientRepository::class);
        self::$scopeRepository = $container->get(ScopeRepository::class);
    }

    public function testFinalizeScopes(): void
    {
        /** @var Client $client */
        $client = self::$clientRepository->getClientEntity('client_private');
        $client->setScopes([
            new Scope(Scopes::PROFILE),
            new Scope(Scopes::OPENID),
        ]);

        $scopes = self::$scopeRepository->finalizeScopes([new Scope(Scopes::PROFILE)],
            GrantTypes::CLIENT_CREDENTIALS, $client);
        $this->assertCount(1, $scopes);

        list($scope) = $scopes;
        $this->assertEquals(Scopes::PROFILE, (string) $scope);
    }
}