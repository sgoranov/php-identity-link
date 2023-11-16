<?php

namespace App\Tests\Repository;

use App\Repository\AuthCodeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuthCodeRepositoryTest extends KernelTestCase
{
    private static AuthCodeRepository $authCodeRepository;

    public static function setUpBeforeClass(): void
    {
        $container = static::getContainer();
        self::$authCodeRepository = $container->get(AuthCodeRepository::class);
    }

    public function testRevokeAuthCode(): void
    {
        list($token) = self::$authCodeRepository->findBy(['identifier' => 'auth_code_identifier']);
        $this->assertFalse(self::$authCodeRepository->isAuthCodeRevoked($token->getIdentifier()));

        self::$authCodeRepository->revokeAuthCode($token->getIdentifier());
        $this->assertTrue(self::$authCodeRepository->isAuthCodeRevoked($token->getIdentifier()));
    }
}