<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;

final class ProductRepository
{
    protected PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getPdo();
    }
    // interacting with the products data source, such as fetching,
    // adding, updating, or deleting products.
    public function getAllProducts(): array
    {

        $stmt = $this->db->query('SELECT * FROM productos');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM productos WHERE id_producto = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    }

    public function createProduct(array $data): int
    {

        $stmt = $this->db->prepare('INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)');
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    public function updateProduct(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock WHERE id_producto = :id');
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function deleteProduct(int $id): bool
    {

        $stmt = $this->db->prepare('DELETE FROM productos WHERE id_producto = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
