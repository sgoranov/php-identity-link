<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientSecret;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        $client = new Client();
        $client->setName('test');
        $client->setIdentifier('test');
        $client->setRedirectUri('http://localhosst');
        $client->setIsConfidential(true);
        $manager->persist($client);

        $secret = new ClientSecret();
        $secret->setClient($client);
        $secret->setSecret(password_hash('secret', PASSWORD_BCRYPT, ["cost" => 10]));
        $secret->setExpiryDateTime($now->modify('+1 day'));
        $manager->persist($secret);

        $manager->flush();
    }
}
