<?php

namespace App\Tests\Repository;

use App\Repository\AccessTokenRepository;
use App\Repository\ClientRepository;
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
        $client = self::$clientRepository->getClientEntity('client_private');

        $token = self::$accessTokenRepository->getNewToken($client, [], 'user_identifier');
        $this->assertEmpty($token->getScopes());

        $token = self::$accessTokenRepository->getNewToken($client, [], 'user_identifier');
        $this->assertEquals('user_identifier', $token->getUserIdentifier());

        $token = self::$accessTokenRepository->getNewToken($client, [], 'user_identifier');
        $this->assertEquals('client_private', $token->getClient()->getIdentifier());
    }

    public function testRevokeAccessToken(): void
    {
        list($token) = self::$accessTokenRepository->findBy(['identifier' => 'access_token_identifier']);
        $this->assertFalse(self::$accessTokenRepository->isAccessTokenRevoked($token->getIdentifier()));

        self::$accessTokenRepository->revokeAccessToken($token->getIdentifier());
        $this->assertTrue(self::$accessTokenRepository->isAccessTokenRevoked($token->getIdentifier()));
    }
}