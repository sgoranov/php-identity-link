<?php
/**
 * Copyright for held by Robin Chalas as part of project thephpleague/oauth2-server-bundle
 * https://github.com/thephpleague/oauth2-server-bundle
 *
 * Copyright (c) 2020 Robin Chalas
 * Portions Copyright (c) 2018-2020 Trikoder
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace App\Tests;

use App\Entity\AuthCode;
use App\Entity\RefreshToken;
use App\Entity\Scope;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoException;
use Defuse\Crypto\Key;
use League\OAuth2\Server\CryptKey;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

final class TestHelper
{
    public const ENCRYPTION_KEY = '4kc8njQtPUazmzZGNt2Wh1rGO6bUFXatJQTnlKimX1Y=';
    public const PRIVATE_KEY_PATH = __DIR__ . '/resources/private.key';
    public const PUBLIC_KEY_PATH = __DIR__ . '/resources/public.key';

    private string $encryptionKeyPath;
    private string $privateKeyPath;

    public function __construct(string $encryptionKeyPath, string $privateKeyPath)
    {
        $this->encryptionKeyPath = $encryptionKeyPath;
        $this->privateKeyPath = $privateKeyPath;
    }

    public function generateEncryptedPayload(RefreshToken $refreshToken): ?string
    {
        $payload = json_encode([
            'client_id' => $refreshToken->getAccessToken()->getClient()->getIdentifier(),
            'refresh_token_id' => $refreshToken->getIdentifier(),
            'access_token_id' => $refreshToken->getAccessToken()->getIdentifier(),
            'scopes' => array_map('strval', $refreshToken->getAccessToken()->getScopes()),
            'user_id' => $refreshToken->getAccessToken()->getUserIdentifier(),
            'expire_time' => $refreshToken->getExpiryDateTime()->getTimestamp(),
        ]);

        try {
            return Crypto::encrypt($payload, Key::loadFromAsciiSafeString(file_get_contents($this->encryptionKeyPath)));
        } catch (CryptoException $e) {
            return null;
        }
    }

    public function generateEncryptedAuthCodePayload(AuthCode $authCode): ?string
    {
        $scopes = array_map(function (Scope $scope): string {
            return $scope->getIdentifier();
        }, $authCode->getScopes());

        $payload = json_encode([
            'client_id' => $authCode->getClient()->getIdentifier(),
            'redirect_uri' => $authCode->getClient()->getRedirectUri(),
            'auth_code_id' => $authCode->getIdentifier(),
            'scopes' => $scopes,
            'user_id' => $authCode->getUserIdentifier(),
            'expire_time' => $authCode->getExpiryDateTime()->getTimestamp(),
            'code_challenge' => null,
            'code_challenge_method' => null,
        ]);

        try {
            return Crypto::encrypt($payload, Key::loadFromAsciiSafeString(file_get_contents($this->encryptionKeyPath)));
        } catch (CryptoException $e) {
            return null;
        }
    }

    public function decryptPayload(string $payload): ?string
    {
        try {
            return Crypto::decrypt($payload, Key::loadFromAsciiSafeString(file_get_contents($this->encryptionKeyPath)));
        } catch (CryptoException $e) {
            return null;
        }
    }

    public function generateJwtToken(AccessTokenModel $accessToken): string
    {
        $clientEntity = new ClientEntity();
        $clientEntity->setIdentifier($accessToken->getClient()->getIdentifier());
        $clientEntity->setRedirectUri(array_map('strval', $accessToken->getClient()->getRedirectUris()));

        $accessTokenEntity = new AccessTokenEntity();
        $accessTokenEntity->setPrivateKey(new CryptKey(self::PRIVATE_KEY_PATH, null, false));
        $accessTokenEntity->setIdentifier($accessToken->getIdentifier());
        $accessTokenEntity->setExpiryDateTime($accessToken->getExpiry());
        $accessTokenEntity->setClient($clientEntity);
        $accessTokenEntity->setUserIdentifier($accessToken->getUserIdentifier());

        foreach ($accessToken->getScopes() as $scope) {
            $scopeEntity = new ScopeEntity();
            $scopeEntity->setIdentifier((string) $scope);

            $accessTokenEntity->addScope($scopeEntity);
        }

        return (string) $accessTokenEntity;
    }
}
