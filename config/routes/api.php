<?php

declare(strict_types=1);

use Slim\App;
use App\Middlewares\AddJsonResponseHeader;
use App\Middlewares\JwtMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response; 

return function (App $app) {
    // Middleware aplicado a TODAS las rutas
    $app->add(new AddJsonResponseHeader());

    // --- GRUPO PRINCIPAL DE LA API (/api) ---
    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {

        // 1. Rutas de Autenticación (Públicas, sin JWT)
        // Ahora se accede a ellas como /api/auth/...
        $group->post('/auth/register', [\App\Controllers\AuthController::class, 'register']);
        $group->post('/auth/login', [\App\Controllers\AuthController::class, 'login']);
        $group->post('/auth/refresh', [\App\Controllers\AuthController::class, 'refresh']);
        $group->post('/auth/logout', [\App\Controllers\AuthController::class, 'logout']);

        // 2. Ruta de Usuario Protegida
        // Ahora se accede a ella como /api/me
        $group->get('/me', function (Request $request, Response $response) {
            $user = $request->getAttribute('user');
            $payload = ['user' => $user];
            $response->getBody()->write(json_encode($payload));
            return $response;
        })->add(new JwtMiddleware()); // Aplica el middleware JWT solo aquí

        // 3. Rutas de Productos
        // Ahora se accede a ellas como /api/products/...
        $group->get('/products', [\App\Controllers\ProductsController::class, 'all']);
        $group->get('/products/{id:[0-9]+}', [\App\Controllers\ProductsController::class, 'byId']);
        // POST, PUT, DELETE already return JSON via controller methods
        $group->post('/products', [\App\Controllers\ProductsController::class, 'create'])->add(new JwtMiddleware());
        $group->put('/products/{id:[0-9]+}', [\App\Controllers\ProductsController::class, 'update'])->add(new JwtMiddleware());
        $group->delete('/products/{id:[0-9]+}', [\App\Controllers\ProductsController::class, 'delete'])->add(new JwtMiddleware());
    });
};
