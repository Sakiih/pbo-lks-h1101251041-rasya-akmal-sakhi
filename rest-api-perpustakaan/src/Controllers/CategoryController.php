<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\CategoryService;
use Throwable;

class CategoryController
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    // GET: Menampilkan semua kategori
    public function index(): void
    {
        $categories = $this->service->list();

        $data = array_map(
            fn($category) => $category->toArray(),
            $categories
        );

        Response::json([
            'success' => true,
            'message' => 'Daftar kategori',
            'data' => $data
        ]);
    }

    // GET: Menampilkan detail kategori
    public function show(int $id): void
    {
        $category = $this->service->findById($id);

        if ($category === null) {
            Response::json([
                'success' => false,
                'message' => "Kategori dengan ID $id tidak ditemukan"
            ], 404);

            return;
        }

        Response::json([
            'success' => true,
            'message' => 'Detail kategori',
            'data' => $category
        ]);
    }

    // POST: Menambahkan kategori
    public function store(Request $request): void
    {
        try {
            $category = $this->service->create($request->getBody());

            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
            ], 201);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    // PUT: Mengubah kategori
    public function update(int $id, Request $request): void
    {
        try {
            $category = $this->service->update(
                $id,
                $request->getBody()
            );

            if ($category === null) {
                Response::json([
                    'success' => false,
                    'message' => "Kategori dengan ID $id tidak ditemukan"
                ], 404);

                return;
            }

            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category
            ]);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    // DELETE: Menghapus kategori
    public function destroy(int $id): void
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            Response::json([
                'success' => false,
                'message' => "Kategori dengan ID $id tidak ditemukan"
            ], 404);

            return;
        }

        Response::json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
