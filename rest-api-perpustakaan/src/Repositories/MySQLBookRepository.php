<?php

namespace App\Repositories;

use PDO;
use App\Models\Book;
use App\Contracts\BookRepositoryInterface;

class MySQLBookRepository implements BookRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM books ORDER BY id DESC";

        $stmt = $this->pdo->query($sql);

        $books = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book(
                (int) $row['id'],
                $row['judul'],
                $row['penulis'],
                (int) $row['tahun'],
                (int) $row['stok']
            );
        }

        return $books;
    }

    public function findById(int $id): ?Book
    {
        $sql = "SELECT * FROM books WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Book(
            (int) $row['id'],
            $row['judul'],
            $row['penulis'],
            (int) $row['tahun'],
            (int) $row['stok']
        );
    }

    public function create(array $data): Book
    {
        $sql = "INSERT INTO books
                (judul, penulis, tahun, stok)
                VALUES
                (:judul, :penulis, :tahun, :stok)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'tahun' => $data['tahun'],
            'stok' => $data['stok']
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return $this->findById($id);
    }

    public function update(int $id, array $data): ?Book
    {
        $sql = "UPDATE books SET
                judul = :judul,
                penulis = :penulis,
                tahun = :tahun,
                stok = :stok
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'tahun' => $data['tahun'],
            'stok' => $data['stok']
        ]);

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM books WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}