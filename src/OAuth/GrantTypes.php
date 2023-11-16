<?php
declare(strict_types=1);

namespace App\OAuth;

class GrantTypes
{
    const CLIENT_CREDENTIALS = 'client_credentials';
    const PASSWORD = 'password';
    const AUTHORIZATION_CODE = 'authorization_code';
    const REFRESH_TOKEN = 'refresh_token';
    const IMPLICIT = 'implicit';

    public static function getSupportedGrantTypes(): array
    {
        return [
            self::CLIENT_CREDENTIALS,
            self::PASSWORD,
            self::AUTHORIZATION_CODE,
            self::REFRESH_TOKEN,
            self::IMPLICIT,
        ];
    }
}