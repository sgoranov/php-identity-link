<?php
declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Model\OAuth2\AuthCodeModel;
use App\ModelMapper\AuthCodeMapper;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeService implements AuthCodeRepositoryInterface
{

    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly AuthCodeMapper $authCodeMapper,
    )
    {
    }

    public function getNewAuthCode(): AuthCodeModel
    {
        return new AuthCodeModel();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->entityManager->persist($this->authCodeMapper->toEntity($authCodeEntity));
        $this->entityManager->flush();
    }

    public function revokeAuthCode($codeId)
    {
        /** @var \App\Repository\AuthCodeRepository $repository */
        $repository = $this->entityManager->getRepository(\App\Entity\AuthCode::class);
        $entity = $repository->getByIdentifier($codeId);
        $entity->setIsRevoked(true);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        /** @var \App\Repository\AuthCodeRepository $repository */
        $repository = $this->entityManager->getRepository(\App\Entity\AuthCode::class);
        $entity = $repository->getByIdentifier($codeId);

        return $entity->isRevoked();
    }
}