<?php

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use Exception;

class BookService
{
    private BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // Menampilkan semua buku
    public function list(): array
    {
        return $this->repository->findAll();
    }

    // Menampilkan detail buku
    public function findById(int $id): ?array
    {
        $book = $this->repository->findById($id);

        return $book?->toArray();
    }

    // Menambahkan buku
    public function create(array $data): array
    {
        if (!isset($data['judul']) || trim((string) $data['judul']) === '') {
            throw new Exception('Judul wajib diisi');
        }

        $book = $this->repository->create($data);

        return $book->toArray();
    }

    // Mengubah buku
    public function update(int $id, array $data): ?array
    {
        if (!isset($data['judul']) || trim((string) $data['judul']) === '') {
            throw new Exception('Judul wajib diisi');
        }

        $book = $this->repository->update($id, $data);

        return $book?->toArray();
    }

    // Menghapus buku
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}