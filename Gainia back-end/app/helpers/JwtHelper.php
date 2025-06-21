<?php

namespace Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static string $secret = 'YOUR_SECRET_KEY';

    public static function generateToken(array $payload, int $expireSeconds = 3600): string
    {
        $payload['exp'] = time() + $expireSeconds;
        return JWT::encode($payload, self::$secret, 'HS256');
    }

    public static function validateToken(string $jwt): ?array
    {
        try {
            return (array) JWT::decode($jwt, new Key(self::$secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}