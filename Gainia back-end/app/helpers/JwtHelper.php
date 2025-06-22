<?php

namespace Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static string $secret = '';

    private static function getSecret(): string
    {
        if (empty(self::$secret)) {
            require __DIR__ . '/../config/jwtconfig.php';
            self::$secret = $jwt_secret;
        }
        return self::$secret;
    }

    public static function generateToken(array $payload, int $expireSeconds = 3600): string
    {
        $payload['exp'] = time() + $expireSeconds;
        return JWT::encode($payload, self::getSecret(), 'HS256');
    }

    public static function validateToken(string $jwt): ?array
    {
        try {
            return (array) JWT::decode($jwt, new Key(self::getSecret(), 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}