<?php
declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\AccessToken;
use App\Entity\AuthCode;
use App\Entity\Client;
use App\Entity\ClientSecret;
use App\Entity\Scope;
use App\OAuth\GrantTypes;
use App\OAuth\Scopes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: "test")]
#[When(env: "dev")]
class AppFixtures extends Fixture
{
    const PUBLIC_CLIENT_IDENTIFIER = 'e48a3bce-773c-40b0-b50c-7ba11e41e062';

    const PRIVATE_CLIENT_IDENTIFIER = '9d080b69-fe45-49ab-95fe-4a1c9b860ca3';
    const PRIVATE_CLIENT_SECRET = '2b740e1d-1655-4ad5-8f20-ee37e4e47f82';
    const PRIVATE_CLIENT_NAME = 'fb85b097-d07a-42fd-b0e5-f701a81082a3';
    const PRIVATE_CLIENT_REDIRECT_URI = 'http://localhost';

    const AUTH_CODE_IDENTIFIER = '000d19bd-4be7-4ce6-ba52-ab7575ffd840';

    const USER_IDENTIFIER = '7c1b7d1b-f624-4966-8f2a-e63ddfc34dba';

    const ACCESS_TOKEN_IDENTIFIER = '6fbc4538-e365-479b-84d9-c881f3259c3f';

    public function load(ObjectManager $manager): void
    {
        // public client
        $client = new Client();
        $client->setName('client_public');
        $client->setIdentifier(self::PUBLIC_CLIENT_IDENTIFIER);
        $client->setRedirectUri('http://localhost/public');
        $client->setIsConfidential(false);
        $client->setGrantTypes([GrantTypes::CLIENT_CREDENTIALS]);
        $client->setScopes([]);
        $manager->persist($client);

        // private client
        $client = new Client();
        $client->setName(self::PRIVATE_CLIENT_NAME);
        $client->setIdentifier(self::PRIVATE_CLIENT_IDENTIFIER);
        $client->setRedirectUri(self::PRIVATE_CLIENT_REDIRECT_URI);
        $client->setIsConfidential(true);
        $client->setGrantTypes([GrantTypes::CLIENT_CREDENTIALS]);
        $client->setScopes([]);
        $manager->persist($client);

        $secret = new ClientSecret();
        $secret->setClient($client);
        $secret->setSecret(password_hash(self::PRIVATE_CLIENT_SECRET, PASSWORD_BCRYPT, ["cost" => 10]));
        $secret->setExpiryDateTime((new \DateTimeImmutable())->modify('+1 day'));
        $manager->persist($secret);

        // auth code
        $code = new AuthCode();
        $code->setClient($client);
        $code->setIsRevoked(false);
        $code->setScopes([new Scope(Scopes::OPENID)]);
        $code->setIdentifier(self::AUTH_CODE_IDENTIFIER);
        $code->setUserIdentifier(self::USER_IDENTIFIER);
        $code->setExpiryDateTime((new \DateTimeImmutable())->modify('+1 day'));
        $code->setRedirectUri(self::PRIVATE_CLIENT_REDIRECT_URI);
        $manager->persist($code);

        // access token
        $token = new AccessToken();
        $token->setClient($client);
        $token->setIsRevoked(false);
        $token->setScopes([new Scope(Scopes::OPENID)]);
        $token->setIdentifier(self::ACCESS_TOKEN_IDENTIFIER);
        $token->setUserIdentifier(self::USER_IDENTIFIER);
        $token->setExpiryDateTime((new \DateTimeImmutable())->modify('+1 day'));
        $manager->persist($token);

        $manager->flush();
    }
}