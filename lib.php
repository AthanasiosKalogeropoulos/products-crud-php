<?php

class Products
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // READ — Όλα τα products
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM products ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    // READ — Ένα product (για edit)
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // CREATE
    public function add_product(string $name, float $price, float $weight): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO products (name, price, weight) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$name, $price, $weight]);
    }

    // UPDATE
    public function update(int $id, string $name, float $price, float $weight): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE products SET name = ?, price = ?, weight = ?, updated_at = NOW() WHERE id = ?'
        );
        return $stmt->execute([$name, $price, $weight, $id]);
    }

    // DELETE
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
