
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;
use App\Core\Container;
use App\Core\Router;
use App\Core\Request;
use App\Controllers\BookController;
use App\Contracts\BookRepositoryInterface;
use App\Repositories\MySQLBookRepository;


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


// Membuat Controller melalui Container
$controller = $container->make(BookController::class);


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


// Menjalankan Router
$router->dispatch($request);
