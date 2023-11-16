<?php
declare(strict_types=1);

namespace App\OAuth;

use App\Repository\AccessTokenRepository;
use App\Repository\AuthCodeRepository;
use App\Repository\ClientRepository;
use App\Repository\RefreshTokenRepository;
use App\Repository\ScopeRepository;
use League\Event\Emitter;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class AuthorizationServerFactory
{
    private string $privateKey;

    private string $encryptionKey;

    private string $accessToken_Ttl;

    private string $refreshTokenTtl;

    private string $authCodeTtl;

    private bool $enableClientCredentialsGrant;

    private bool $enablePasswordGrant;

    private bool $enableRefreshTokenGrant;

    private bool $enableAuthCodeGrant;

    private bool $enableImplicitGrant;

    public function __construct(
        readonly AccessTokenRepository $accessTokenRepository,
        readonly AuthCodeRepository $authCodeRepository,
        readonly ClientRepository $clientRepository,
        readonly RefreshTokenRepository $refreshTokenRepository,
        readonly ScopeRepository $scopeRepository,
        readonly UserRepositoryInterface $userRepository,
        readonly Emitter $emitter,
    )
    {
    }

    public function setPrivateKey(string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }

    public function setEncryptionKey(string $encryptionKey): void
    {
        $this->encryptionKey = $encryptionKey;
    }

    public function setAccessTokenTtl(string $accessToken_Ttl): void
    {
        $this->accessToken_Ttl = $accessToken_Ttl;
    }

    public function setRefreshTokenTtl(string $refreshTokenTtl): void
    {
        $this->refreshTokenTtl = $refreshTokenTtl;
    }

    public function setAuthCodeTtl(string $authCodeTtl): void
    {
        $this->authCodeTtl = $authCodeTtl;
    }

    public function enableClientCredentialsGrant(bool $enableClientCredentialsGrant): void
    {
        $this->enableClientCredentialsGrant = $enableClientCredentialsGrant;
    }

    public function enablePasswordGrant(bool $enablePasswordGrant): void
    {
        $this->enablePasswordGrant = $enablePasswordGrant;
    }

    public function enableRefreshTokenGrant(bool $enableRefreshTokenGrant): void
    {
        $this->enableRefreshTokenGrant = $enableRefreshTokenGrant;
    }

    public function enableAuthCodeGrant(bool $enableAuthCodeGrant): void
    {
        $this->enableAuthCodeGrant = $enableAuthCodeGrant;
    }

    public function enableImplicitGrant(bool $enableImplicitGrant): void
    {
        $this->enableImplicitGrant = $enableImplicitGrant;
    }

    public function create(): AuthorizationServer
    {
        $server = new AuthorizationServer(
            $this->clientRepository,
            $this->accessTokenRepository,
            $this->scopeRepository,
            $this->privateKey,
            $this->encryptionKey
        );

        $server->setEmitter($this->emitter);

        if ($this->enableClientCredentialsGrant) {
            $grantType = new ClientCredentialsGrant();
            $server->enableGrantType($grantType, new \DateInterval($this->accessToken_Ttl));
        }

        if ($this->enablePasswordGrant) {
            $grantType = new PasswordGrant($this->userRepository, $this->refreshTokenRepository);
            $grantType->setRefreshTokenTTL(new \DateInterval($this->refreshTokenTtl));
            $server->enableGrantType($grantType, new \DateInterval($this->accessToken_Ttl));
        }

        if ($this->enableRefreshTokenGrant) {
            $grantType = new RefreshTokenGrant($this->refreshTokenRepository);
            $grantType->setRefreshTokenTTL(new \DateInterval($this->refreshTokenTtl));
            $server->enableGrantType($grantType, new \DateInterval($this->accessToken_Ttl));
        }

        if ($this->enableAuthCodeGrant) {
            $grantType = new AuthCodeGrant($this->authCodeRepository, $this->refreshTokenRepository,
                new \DateInterval($this->authCodeTtl));
            $grantType->setRefreshTokenTTL(new \DateInterval($this->refreshTokenTtl));
            $server->enableGrantType($grantType, new \DateInterval($this->accessToken_Ttl));
        }

        if ($this->enableImplicitGrant) {
            $grantType = new ImplicitGrant(new \DateInterval($this->accessToken_Ttl));
            $server->enableGrantType($grantType, new \DateInterval($this->accessToken_Ttl));
        }

        return $server;
    }
}