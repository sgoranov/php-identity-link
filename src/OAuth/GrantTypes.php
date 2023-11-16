<?php

namespace App\OAuth;

class GrantTypes
{
    const CLIENT_CREDENTIALS = 'client_credentials';
    const PASSWORD = 'password';
    const AUTHORIZATION_CODE = 'authorization_code';
    const REFRESH_TOKEN = 'refresh_token';

    public static function getSupportedGrantTypes(): array
    {
        return [
            self::CLIENT_CREDENTIALS,
            self::PASSWORD,
            self::AUTHORIZATION_CODE,
            self::REFRESH_TOKEN,
        ];
    }
}