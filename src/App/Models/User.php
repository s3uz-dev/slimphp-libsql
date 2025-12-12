<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class User
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getPdo();
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare('INSERT INTO users (email, password, name, role) VALUES (:email, :password, :name, :role)');
        $stmt->execute([
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name'] ?? null,
            'role' => $data['role'] ?? 'user'
        ]);
        return $this->findById((int) $this->db->lastInsertId());
    }


}
