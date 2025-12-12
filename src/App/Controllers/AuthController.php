<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\User;
use App\Utils\JwtHelper;
use App\Database\Database;
use PDO;
use DateTime;
use DateTimeZone;

class AuthController
{
    protected User $userModel;
    protected PDO $db;

    public function __construct(Database $database, User $userModel)
    {
        $this->userModel = $userModel;
        $this->db = $database->getPdo();
    }

    protected function json(Response $response, $data, int $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    public function register(Request $request, Response $response)
    {
        $data = (array) $request->getParsedBody();
        if (empty($data['email']) || empty($data['password'])) {
            return $this->json($response, ['error' => 'email and password required'], 422);
        }
        if ($this->userModel->findByEmail($data['email'])) {
            return $this->json($response, ['error' => 'email exists'], 409);
        }
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = $this->userModel->create([
            'email' => $data['email'],
            'password' => $hashed,
            'name' => $data['name'] ?? null
        ]);
        unset($user['password']);
        return $this->json($response, ['user' => $user], 201);
    }

    public function login(Request $request, Response $response)
    {
        $data = (array) $request->getParsedBody();
        if (empty($data['email']) || empty($data['password'])) {
            return $this->json($response, ['error' => 'email and password required'], 422);
        }
        $user = $this->userModel->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->json($response, ['error' => 'invalid credentials'], 401);
        }
        $accessToken = JwtHelper::generateAccessToken($user);
        $refreshToken = JwtHelper::generateRefreshToken();
        $expiresAt = (new DateTime('+' . (int) (getenv('REFRESH_EXPIRES_DAYS') ?: 30) . ' days', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $stmt = $this->db->prepare('INSERT INTO refresh_tokens (user_id, token_hash, expires_at) VALUES (:user_id, :token_hash, :expires_at)');
        $stmt->execute([
            'user_id' => $user['id'],
            'token_hash' => password_hash($refreshToken, PASSWORD_DEFAULT),
            'expires_at' => $expiresAt
        ]);

        return $this->json($response, [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => (int) (getenv('JWT_EXPIRES') ?: 900)
        ], 200);
    }

    public function refresh(Request $request, Response $response)
    {
        $data = (array) $request->getParsedBody();
        if (empty($data['refresh_token'])) {
            return $this->json($response, ['error' => 'refresh_token required'], 422);
        }
        $refresh = $data['refresh_token'];

        $stmt = $this->db->prepare('SELECT * FROM refresh_tokens WHERE expires_at > NOW()');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $found = null;
        foreach ($rows as $row) {
            if (password_verify($refresh, $row['token_hash'])) {
                $found = $row;
                break;
            }
        }
        if (!$found) {
            return $this->json($response, ['error' => 'invalid refresh token'], 401);
        }

        $this->db->prepare('DELETE FROM refresh_tokens WHERE id = :id')->execute(['id' => $found['id']]);

        $user = $this->userModel->findById((int) $found['user_id']);
        if (!$user) {
            return $this->json($response, ['error' => 'user not found'], 404);
        }

        $accessToken = JwtHelper::generateAccessToken($user);
        $newRefresh = JwtHelper::generateRefreshToken();
        $expiresAt = (new DateTime('+' . (int) (getenv('REFRESH_EXPIRES_DAYS') ?: 30) . ' days', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare('INSERT INTO refresh_tokens (user_id, token_hash, expires_at) VALUES (:user_id, :token_hash, :expires_at)');
        $stmt->execute([
            'user_id' => $user['id'],
            'token_hash' => password_hash($newRefresh, PASSWORD_DEFAULT),
            'expires_at' => $expiresAt
        ]);

        return $this->json($response, [
            'access_token' => $accessToken,
            'refresh_token' => $newRefresh,
            'token_type' => 'bearer',
            'expires_in' => (int) (getenv('JWT_EXPIRES') ?: 900)
        ], 200);
    }

    public function logout(Request $request, Response $response)
    {
        $data = (array) $request->getParsedBody();
        if (empty($data['refresh_token'])) {
            return $this->json($response, ['error' => 'refresh_token required'], 422);
        }
        $refresh = $data['refresh_token'];

        $stmt = $this->db->prepare('SELECT * FROM refresh_tokens WHERE expires_at > NOW()');
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            if (password_verify($refresh, $row['token_hash'])) {
                $this->db->prepare('DELETE FROM refresh_tokens WHERE id = :id')->execute(['id' => $row['id']]);
                break;
            }
        }
        return $this->json($response, ['status' => 'logged out'], 200);
    }
}
