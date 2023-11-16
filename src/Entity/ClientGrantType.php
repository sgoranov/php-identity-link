<?php

namespace App\Entity;

use App\Repository\ClientGrantTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientGrantTypeRepository::class)]
class ClientGrantType
{
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';
    const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $client;

    #[ORM\Column(length: 25)]
    private string $grantType;

    public function getId(): string
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function setGrantType(string $grantType): void
    {
        if (!in_array($grantType, [
            self::GRANT_TYPE_CLIENT_CREDENTIALS,
            self::GRANT_TYPE_PASSWORD,
            self::GRANT_TYPE_AUTHORIZATION_CODE,
            self::GRANT_TYPE_REFRESH_TOKEN,
        ])) {
            throw new \InvalidArgumentException(sprintf('Invalid grant type %s passed.', $grantType));
        }

        $this->grantType = $grantType;
    }
}
