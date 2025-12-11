<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

final class ProductRepository
{
    public function __construct(private Database $db)
    {

    }
    // interacting with the products data source, such as fetching,
    // adding, updating, or deleting products.
    public function getAllProducts(): array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->query('SELECT * FROM productos');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById(int $id): ?array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM productos WHERE id_producto = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    }

    public function createProduct(array $data): int
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)');
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->execute();
        return (int)$conn->lastInsertId();
    }

    public function updateProduct(int $id, array $data): bool
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock WHERE id_producto = :id');
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }   
    public function deleteProduct(int $id): bool
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('DELETE FROM productos WHERE id_producto = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
