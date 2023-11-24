<?php
declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\Client;
use App\Model\OAuth2\ScopeModel;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeService implements ScopeRepositoryInterface
{
    public function getScopeEntityByIdentifier($identifier): ScopeModel
    {
        return new ScopeModel($identifier);
    }

    /**
     * @param array $scopes
     * @param string $grantType
     * @param Client $clientEntity
     * @param string $userIdentifier
     * @return array
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        $availableScopes = $clientEntity->getScopes();

        if (empty($availableScopes)) {
            return $scopes;
        }

        if (empty($scopes)) {
            return $availableScopes;
        }

        $availableScopesAsStrings = array_map('strval', $availableScopes);
        foreach ($scopes as $scope) {
            if (!in_array((string) $scope, $availableScopesAsStrings, true)) {
                throw new \InvalidArgumentException(sprintf('Invalid scope %s passed.', $scope));
            }
        }

        return $scopes;
    }
}
