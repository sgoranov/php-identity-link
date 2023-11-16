<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Scope;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

trait ScopeTrait
{
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $scopes = null;

    public function addScope(ScopeEntityInterface $scope): void
    {
        $scopes = $this->getScopes();
        foreach ($scopes as $obj) {
            if ($obj->getIdentifier() === $scope->getIdentifier()) {
                return;
            }
        }

        $scopes[] = $scope;
        $this->scopes = json_encode($scopes);
    }

    /**
     * @return array|ScopeEntityInterface[]
     */
    public function getScopes()
    {
        if (empty($this->scopes)) {
            return [];
        }

        $scopes = [];
        foreach (json_decode($this->scopes, true) as $id) {
            $scopes[] = new Scope($id);
        }

        return $scopes;
    }

    /**
     * @param Scope[] $scopes
     * @return void
     */
    public function setScopes(array $scopes): void
    {
        $this->scopes = json_encode($scopes);
    }
}