<?php
declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Repository\AccessTokenRepository;
use App\Repository\ClientRepository;
use App\Tests\Fixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccessTokenRepositoryTest extends KernelTestCase
{
    private static ClientRepository $clientRepository;
    private static AccessTokenRepository $accessTokenRepository;

    public static function setUpBeforeClass(): void
    {
        $container = static::getContainer();
        self::$accessTokenRepository = $container->get(AccessTokenRepository::class);
        self::$clientRepository = $container->get(ClientRepository::class);
    }

    public function testGetNewToken(): void
    {
        $client = self::$clientRepository->getClientEntity(AppFixtures::PRIVATE_CLIENT_IDENTIFIER);

        $token = self::$accessTokenRepository->getNewToken($client, [], AppFixtures::USER_IDENTIFIER);
        $this->assertEmpty($token->getScopes());

        $token = self::$accessTokenRepository->getNewToken($client, [], AppFixtures::USER_IDENTIFIER);
        $this->assertEquals(AppFixtures::USER_IDENTIFIER, $token->getUserIdentifier());

        $token = self::$accessTokenRepository->getNewToken($client, [], AppFixtures::USER_IDENTIFIER);
        $this->assertEquals(AppFixtures::PRIVATE_CLIENT_IDENTIFIER, $token->getClient()->getIdentifier());

        $token = self::$accessTokenRepository->getNewToken($client, [], null);
        $this->assertNull($token->getUserIdentifier());
    }

    public function testRevokeAccessToken(): void
    {
        list($token) = self::$accessTokenRepository->findBy(['identifier' => AppFixtures::ACCESS_TOKEN_IDENTIFIER]);
        $this->assertFalse(self::$accessTokenRepository->isAccessTokenRevoked($token->getIdentifier()));

        self::$accessTokenRepository->revokeAccessToken($token->getIdentifier());
        $this->assertTrue(self::$accessTokenRepository->isAccessTokenRevoked($token->getIdentifier()));
    }
}