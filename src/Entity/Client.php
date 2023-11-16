<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ClientEntityInterface;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements ClientEntityInterface
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

    #[ORM\Column(length: 100)]
    private string $identifier;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 500)]
    private ?string $redirectUri = null;

    #[ORM\Column]
    private bool $isConfidential = true;

    #[ORM\Column(type: "text")]
    private string $grantTypes;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri(?string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    public function setIsConfidential(?bool $isConfidential): void
    {
        $this->isConfidential = $isConfidential;
    }

    public function isConfidential(): ?bool
    {
        return $this->isConfidential;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGrantTypes(): array
    {
        return json_decode($this->grantTypes, JSON_OBJECT_AS_ARRAY);
    }

    public function setGrantTypes(array $grantTypes): void
    {
        $grantTypes = array_unique(array_values($grantTypes));

        foreach ($grantTypes as $grantType) {
            if (!in_array($grantType, [
                self::GRANT_TYPE_CLIENT_CREDENTIALS,
                self::GRANT_TYPE_PASSWORD,
                self::GRANT_TYPE_AUTHORIZATION_CODE,
                self::GRANT_TYPE_REFRESH_TOKEN,
            ])) {
                throw new \InvalidArgumentException(sprintf('Invalid grant type %s passed.', $grantType));
            }
        }

        $this->grantTypes = json_encode($grantTypes);
    }
}
