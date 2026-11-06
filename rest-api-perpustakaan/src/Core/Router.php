<?php

namespace App\Core;

use App\Core\Request;
use App\Core\Response;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $handler): void
    {
        $this->addRoute('GET', $uri, $handler);
    }

    public function post(string $uri, callable $handler): void
    {
        $this->addRoute('POST', $uri, $handler);
    }

    public function put(string $uri, callable $handler): void
    {
        $this->addRoute('PUT', $uri, $handler);
    }

    public function delete(string $uri, callable $handler): void
    {
        $this->addRoute('DELETE', $uri, $handler);
    }

    private function addRoute(
        string $method,
        string $uri,
        callable $handler
    ): void {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'handler' => $handler
        ];
    }

    public function dispatch(Request $request): void
    {
        foreach ($this->routes as $route) {

            $pattern = preg_replace_callback(
                '/\\\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\\\}/',
                fn($matches) => '(?P<' . $matches[1] . '>[^/]+)',
                preg_quote($route['uri'], '~')
            );

            if (
                $request->getMethod() === $route['method'] &&
                preg_match(
                    '~^' . $pattern . '$~',
                    $request->getUri(),
                    $matches
                )
            ) {
                $params = array_filter(
                    $matches,
                    'is_string',
                    ARRAY_FILTER_USE_KEY
                );

                call_user_func_array(
                    $route['handler'],
                    array_values($params)
                );

                return;
            }
        }

        Response::json([
            'success' => false,
            'message' => 'Endpoint tidak ditemukan: ' . $request->getUri()
        ], 404);
    }
}