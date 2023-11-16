<?php
declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Repository\AuthCodeRepository;
use App\Tests\Fixtures\AppFixtures;
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
        list($token) = self::$authCodeRepository->findBy(['identifier' => AppFixtures::AUTH_CODE_IDENTIFIER]);
        $this->assertFalse(self::$authCodeRepository->isAuthCodeRevoked($token->getIdentifier()));

        self::$authCodeRepository->revokeAuthCode($token->getIdentifier());
        $this->assertTrue(self::$authCodeRepository->isAuthCodeRevoked($token->getIdentifier()));
    }
}