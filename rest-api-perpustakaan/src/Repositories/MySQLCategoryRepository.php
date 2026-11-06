<?php

namespace App\Repositories;

use PDO;
use App\Models\Category;
use App\Contracts\CategoryRepositoryInterface;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM categories ORDER BY id DESC";

        $stmt = $this->pdo->query($sql);

        $categories = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category(
                (int) $row['id'],
                $row['nama'],
                $row['deskripsi']
            );
        }

        return $categories;
    }

    public function findById(int $id): ?Category
    {
        $sql = "SELECT * FROM categories WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Category(
            (int) $row['id'],
            $row['nama'],
            $row['deskripsi']
        );
    }

    public function create(array $data): Category
    {
        $sql = "INSERT INTO categories
                (nama, deskripsi)
                VALUES
                (:nama, :deskripsi)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return $this->findById($id);
    }

    public function update(int $id, array $data): ?Category
    {
        $sql = "UPDATE categories SET
                nama = :nama,
                deskripsi = :deskripsi
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null
        ]);

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM categories WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}