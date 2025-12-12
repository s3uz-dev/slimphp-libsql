<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    public static function generateAccessToken(array $userPayload)
    {
        $secret = getenv('JWT_SECRET') ?: 'change_me';
        $now = time();
        $exp = $now + (int)(getenv('JWT_EXPIRES') ?: 900);
        $payload = [
            'iat' => $now,
            'exp' => $exp,
            'sub' => $userPayload['id'],
            'email' => $userPayload['email'],
            'role' => $userPayload['role'] ?? 'user'
        ];
        return JWT::encode($payload, $secret, 'HS256');
    }

    public static function verifyAccessToken(string $token)
    {
        $secret = getenv('JWT_SECRET') ?: 'change_me';
        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return (array)$decoded;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function generateRefreshToken()
    {
        return bin2hex(random_bytes(64));
    }
}
