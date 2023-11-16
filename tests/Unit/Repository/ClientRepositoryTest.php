<?php
declare(strict_types=1);

namespace  App\Tests\Unit\Repository;

use App\OAuth\GrantTypes;
use App\Repository\ClientRepository;
use App\Tests\Fixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
        $client = self::$clientRepository->getClientEntity(AppFixtures::PRIVATE_CLIENT_IDENTIFIER);
        $this->assertEquals(AppFixtures::PRIVATE_CLIENT_NAME, $client->getName());

        $client = self::$clientRepository->getClientEntity('no_such_client');
        $this->assertNull($client);
    }

    public function testPublicClientValidity(): void
    {
        $result = self::$clientRepository->validateClient(
            AppFixtures::PUBLIC_CLIENT_IDENTIFIER,
            null,
            GrantTypes::AUTHORIZATION_CODE
        );
        $this->assertFalse($result);

        $result = self::$clientRepository->validateClient(
            AppFixtures::PUBLIC_CLIENT_IDENTIFIER,
            null,
            GrantTypes::CLIENT_CREDENTIALS
        );
        $this->assertTrue($result);
    }

    public function testPrivateClientValidity(): void
    {
        // invalid grant type
        $result = self::$clientRepository->validateClient(
            AppFixtures::PRIVATE_CLIENT_IDENTIFIER,
            AppFixtures::PRIVATE_CLIENT_SECRET,
            GrantTypes::AUTHORIZATION_CODE);
        $this->assertFalse($result);

        // valid grant type
        $result = self::$clientRepository->validateClient(
            AppFixtures::PRIVATE_CLIENT_IDENTIFIER,
            AppFixtures::PRIVATE_CLIENT_SECRET,
            GrantTypes::CLIENT_CREDENTIALS);
        $this->assertTrue($result);
    }
}