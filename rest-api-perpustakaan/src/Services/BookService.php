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

    public function list(): array
    {
        return $this->repository->findAll();
    }

    public function create(array $data): array
    {
        if (!isset($data['judul']) || trim((string) $data['judul']) === '') {
            throw new Exception('Judul wajib diisi');
        }

        $book = $this->repository->create($data);

        return $book->toArray();
    }
}