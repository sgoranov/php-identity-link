<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientGrantType;
use App\Entity\ClientSecret;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        // client with secret
        $client = new Client();
        $client->setName('test');
        $client->setIdentifier('test');
        $client->setRedirectUri('http://localhost');
        $client->setIsConfidential(true);
        $manager->persist($client);

        $secret = new ClientSecret();
        $secret->setClient($client);
        $secret->setSecret(password_hash('secret', PASSWORD_BCRYPT, ["cost" => 10]));
        $secret->setExpiryDateTime($now->modify('+1 day'));
        $manager->persist($secret);

        $grantType = new ClientGrantType();
        $grantType->setGrantType(ClientGrantType::GRANT_TYPE_CLIENT_CREDENTIALS);
        $grantType->setClient($client);
        $manager->persist($grantType);

        // public client, i.e. without secret
        $client = new Client();
        $client->setName('test_public');
        $client->setIdentifier('v');
        $client->setRedirectUri('http://localhost');
        $client->setIsConfidential(false);
        $manager->persist($client);

        $grantType = new ClientGrantType();
        $grantType->setGrantType(ClientGrantType::GRANT_TYPE_CLIENT_CREDENTIALS);
        $grantType->setClient($client);
        $manager->persist($grantType);

        $manager->flush();
    }
}
