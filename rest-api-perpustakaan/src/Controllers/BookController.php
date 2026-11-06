<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\BookService;
use Throwable;

class BookController
{
    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    // GET: Menampilkan semua buku
    public function index(): void
    {
        $books = $this->service->list();

        $data = array_map(
            fn($book) => $book->toArray(),
            $books
        );

        Response::json([
            'success' => true,
            'message' => 'Daftar buku',
            'data' => $data
        ]);
    }

    // GET: Menampilkan detail buku
    public function show(int $id): void
    {
        $book = $this->service->findById($id);

        if ($book === null) {
            Response::json([
                'success' => false,
                'message' => "Buku dengan ID $id tidak ditemukan"
            ], 404);

            return;
        }

        Response::json([
            'success' => true,
            'message' => 'Detail buku',
            'data' => $book
        ]);
    }

    // POST: Menambahkan buku
    public function store(Request $request): void
    {
        try {
            $book = $this->service->create($request->getBody());

            Response::json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $book
            ], 201);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    // PUT: Mengubah buku
    public function update(int $id, Request $request): void
    {
        try {
            $book = $this->service->update(
                $id,
                $request->getBody()
            );

            if ($book === null) {
                Response::json([
                    'success' => false,
                    'message' => "Buku dengan ID $id tidak ditemukan"
                ], 404);

                return;
            }

            Response::json([
                'success' => true,
                'message' => 'Buku berhasil diperbarui',
                'data' => $book
            ]);

        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    // DELETE: Menghapus buku
    public function destroy(int $id): void
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            Response::json([
                'success' => false,
                'message' => "Buku dengan ID $id tidak ditemukan"
            ], 404);

            return;
        }

        Response::json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}
