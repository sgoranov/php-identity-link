<?php

namespace App\Tests\DataFixtures;

use App\Entity\AccessToken;
use App\Entity\Client;
use App\Entity\ClientSecret;
use App\Entity\Scope;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: "test")]
#[When(env: "dev")]
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // public client
        $client = new Client();
        $client->setName('client_public');
        $client->setIdentifier('client_public');
        $client->setRedirectUri('http://localhost');
        $client->setIsConfidential(false);
        $client->setGrantTypes([Client::GRANT_TYPE_CLIENT_CREDENTIALS]);
        $manager->persist($client);

        // private client
        $client = new Client();
        $client->setName('client_private');
        $client->setIdentifier('client_private');
        $client->setRedirectUri('http://localhost');
        $client->setIsConfidential(true);
        $client->setGrantTypes([Client::GRANT_TYPE_CLIENT_CREDENTIALS]);
        $manager->persist($client);

        $secret = new ClientSecret();
        $secret->setClient($client);
        $secret->setSecret(password_hash('secret', PASSWORD_BCRYPT, ["cost" => 10]));
        $secret->setExpiryDateTime((new \DateTimeImmutable())->modify('+1 day'));
        $manager->persist($secret);

        // access token
        $token = new AccessToken();
        $token->setClient($client);
        $token->setIsRevoked(false);
        $token->setScopes([new Scope(Scope::SCOPE_OPENID)]);
        $token->setIdentifier('access_token_identifier');
        $token->setUserIdentifier('access_token_user_identifier');
        $token->setExpiryDateTime((new \DateTimeImmutable())->modify('+1 day'));
        $manager->persist($token);

        $manager->flush();
    }
}
