
<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;
use App\Core\Container;
use App\Core\Router;
use App\Core\Request;
use App\Controllers\BookController;
use App\Controllers\CategoryController;
use App\Contracts\BookRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
use App\Repositories\MySQLBookRepository;
use App\Repositories\MySQLCategoryRepository;
use App\Services\CategoryService;


// Membaca konfigurasi .env
Env::load(dirname(__DIR__) . '/.env');


// Membuat Container
$container = new Container();


// Binding koneksi PDO
$container->bind(\PDO::class, function () {
    return Connection::get();
});


// Binding Repository Interface
$container->bind(
    BookRepositoryInterface::class,
    function ($container) {
        return new MySQLBookRepository(
            $container->make(\PDO::class)
        );
    }
);


// Binding Repository Kategori
$container->bind(
    CategoryRepositoryInterface::class,
    function ($container) {
        return new MySQLCategoryRepository(
            $container->make(\PDO::class)
        );
    }
);


// Binding Service Kategori
$container->bind(
    CategoryService::class,
    function ($container) {
        return new CategoryService(
            $container->make(CategoryRepositoryInterface::class)
        );
    }
);


// Binding Controller Kategori
$container->bind(
    CategoryController::class,
    function ($container) {
        return new CategoryController(
            $container->make(CategoryService::class)
        );
    }
);


// Membuat Controller melalui Container
$controller = $container->make(BookController::class);


$categoryController = $container->make(CategoryController::class);


// Membuat satu objek Request
$request = new Request();


// Membuat Router
$router = new Router();


// GET: Menampilkan semua buku
$router->get('/api/books', function () use ($controller) {
    $controller->index();
});


// GET: Menampilkan detail buku
$router->get('/api/books/{id}', function ($id) use ($controller) {
    $controller->show((int) $id);
});


// POST: Menambahkan buku
$router->post('/api/books', function () use ($controller, $request) {
    $controller->store($request);
});


// PUT: Mengubah buku
$router->put('/api/books/{id}', function ($id) use ($controller, $request) {
    $controller->update((int) $id, $request);
});


// DELETE: Menghapus buku
$router->delete('/api/books/{id}', function ($id) use ($controller) {
    $controller->destroy((int) $id);
});


// GET: Menampilkan semua kategori
$router->get('/api/categories', function () use ($categoryController) {
    $categoryController->index();
});


// GET: Menampilkan detail kategori
$router->get('/api/categories/{id}', function ($id) use ($categoryController) {
    $categoryController->show((int) $id);
});


// POST: Menambahkan kategori
$router->post('/api/categories', function () use ($categoryController, $request) {
    $categoryController->store($request);
});


// PUT: Mengubah kategori
$router->put('/api/categories/{id}', function ($id) use ($categoryController, $request) {
    $categoryController->update((int) $id, $request);
});


// DELETE: Menghapus kategori
$router->delete('/api/categories/{id}', function ($id) use ($categoryController) {
    $categoryController->destroy((int) $id);
});


// Menjalankan Router
$router->dispatch($request);
