<?php

namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use Exception;

class CategoryService
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // Menampilkan semua kategori
    public function list(): array
    {
        return $this->repository->findAll();
    }

    // Menampilkan detail kategori
    public function findById(int $id): ?array
    {
        $category = $this->repository->findById($id);

        return $category?->toArray();
    }

    // Menambahkan kategori
    public function create(array $data): array
    {
        if (!isset($data['nama']) || trim((string) $data['nama']) === '') {
            throw new Exception('Nama kategori wajib diisi');
        }

        $category = $this->repository->create($data);

        return $category->toArray();
    }

    // Mengubah kategori
    public function update(int $id, array $data): ?array
    {
        if (!isset($data['nama']) || trim((string) $data['nama']) === '') {
            throw new Exception('Nama kategori wajib diisi');
        }

        $category = $this->repository->update($id, $data);

        return $category?->toArray();
    }

    // Menghapus kategori
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
