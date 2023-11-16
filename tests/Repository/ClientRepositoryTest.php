<?php

namespace  App\Tests\Repository;

use App\OAuth\GrantTypes;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

// php bin/console --env=test doctrine:database:create
// php bin/console --env=test doctrine:schema:create
// php bin/console --env=test doctrine:fixtures:load
// php bin/phpunit tests/Repository/ClientRepositoryTest.php
class ClientRepositoryTest extends KernelTestCase
{
    private static ClientRepository $clientRepository;

    public static function setUpBeforeClass(): void
    {
        $container = static::getContainer();
        self::$clientRepository = $container->get(ClientRepository::class);
    }

    public function testGetClientByIdentifier(): void
    {
        $client = self::$clientRepository->getClientEntity('client_private');
        $this->assertEquals('client_private', $client->getName());

        $client = self::$clientRepository->getClientEntity('no_such_client');
        $this->assertNull($client);
    }

    public function testPublicClientValidity(): void
    {
        $result = self::$clientRepository->validateClient('client_public', null,
            GrantTypes::AUTHORIZATION_CODE);
        $this->assertFalse($result);

        $result = self::$clientRepository->validateClient('client_public', null,
            GrantTypes::CLIENT_CREDENTIALS);
        $this->assertTrue($result);
    }

    public function testPrivateClientValidity(): void
    {
        $result = self::$clientRepository->validateClient('client_private', 'secret',
            GrantTypes::AUTHORIZATION_CODE);
        $this->assertFalse($result);

        $result = self::$clientRepository->validateClient('client_private', 'secret',
            GrantTypes::CLIENT_CREDENTIALS);
        $this->assertTrue($result);
    }
}