<?php
declare(strict_types=1);

namespace App\Model\OAuth2;

use App\Model\OAuth2\Traits\SerializableTrait;

class GrantTypeModel
{
    use SerializableTrait;

    const CLIENT_CREDENTIALS = 'client_credentials';
    const PASSWORD = 'password';
    const AUTHORIZATION_CODE = 'authorization_code';
    const REFRESH_TOKEN = 'refresh_token';
    const IMPLICIT = 'implicit';

    public static function getSupported(): array
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