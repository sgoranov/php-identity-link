<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientSecret;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        return $this->findOneBy(['identifier' => $clientIdentifier]);
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $client = $this->findOneBy(['identifier' => $clientIdentifier]);
        if (is_null($client)) {
            throw new \InvalidArgumentException(sprintf('Client with identifier %s was not found.', $clientIdentifier));
        }

        if ($grantType !== null && !in_array($grantType, $client->getGrantTypes())) {
            return false;
        }

        if ($clientSecret === null) {

            if ($client->isConfidential()) {
                return false;
            }

            return  true;
        }

        // check client secret
        $result = $this->createQueryBuilder('t')
            ->select('s')
            ->innerJoin(ClientSecret::class, 's', Join::WITH, 's.client = t.id')
            ->andWhere('s.expiryDateTime >= :now')
            ->setParameter('now', new DateTime())
            ->getQuery()
            ->getResult()
        ;

        /** @var ClientSecret $secret */
        foreach ($result as $secret) {
            if (password_verify($clientSecret, $secret->getSecret())) {
                return true;
            }
        }

        return false;
    }
}
