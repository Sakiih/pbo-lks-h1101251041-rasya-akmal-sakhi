<?php

require 'vendor/autoload.php';

use App\Core\Router;
use App\Core\Request;
use App\Core\Container;

// Simulasi URI
$_SERVER['REQUEST_URI'] = '/api/hello';
$_SERVER['REQUEST_METHOD'] = 'GET';

$router = new Router();

$router->get('/api/hello', function() {
    echo "✅ Router bekerja! Endpoint /api/hello terpanggil." . PHP_EOL;
});

$router->dispatch(new Request());

// Pengujian Container
$container = new Container();

$container->bind('nama', fn() => 'Rasya');

$hasil = $container->make('nama');

if ($hasil !== 'Rasya') {
    throw new Exception('Container gagal!');
}