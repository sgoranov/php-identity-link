<?php
declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\Client;
use App\Entity\ClientSecret;
use App\Model\OAuth2\GrantTypeModel;
use App\ModelMapper\ClientMapper;
use App\Repository\ClientRepository;
use App\Repository\ClientSecretRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientService implements ClientRepositoryInterface
{

    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly ClientMapper $clientMapper,
    )
    {
    }

    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        /** @var ClientRepository $repository */
        $repository = $this->entityManager->getRepository(Client::class);

        $entity = $repository->findOneBy(['identifier' => $clientIdentifier]);
        if ($entity === null) {
            return null;
        }

        return $this->clientMapper->toModel($entity);
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        /** @var ClientRepository $repository */
        $repository = $this->entityManager->getRepository(Client::class);

        /** @var Client $client */
        $client = $repository->findOneBy(['identifier' => $clientIdentifier]);
        if ($client === null) {
            throw new \InvalidArgumentException(sprintf('Client with identifier %s was not found.', $clientIdentifier));
        }

        if ($grantType !== null
            && !in_array($grantType, GrantTypeModel::convertToStringArray($client->getGrantTypes()), true)) {
            return false;
        }

        if ($clientSecret === null) {

            if ($client->isConfidential()) {
                return false;
            }

            return  true;
        }

        /** @var ClientSecretRepository $secretRepository */
        $secretRepository = $this->entityManager->getRepository(ClientSecret::class);

        return $secretRepository->validateClientSecret($client->getIdentifier(), $clientSecret);
    }
}