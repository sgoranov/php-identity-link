<?php

namespace App\Service;

final class PasswordHashGenerator
{
    public static function create(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
    }
}