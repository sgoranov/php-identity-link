<?php
declare(strict_types=1);

namespace App\OAuth;

class Scopes
{
    const OPENID = 'openid';
    const PROFILE = 'profile';
    const GROUPS = 'groups';

    public static function getSupportedAsString(): array
    {
        return [
            self::GROUPS,
            self::OPENID,
            self::PROFILE
        ];
    }
}