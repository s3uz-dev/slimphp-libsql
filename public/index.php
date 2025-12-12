<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Middlewares\AddJsonResponseHeader;
use App\Middlewares\JwtMiddleware;
use DI\ContainerBuilder;
use Valitron\Validator;

// Load environment variables (use Unsafe to export to putenv for getenv() access)
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Set Valitron language (Spanish)
Validator::lang('es');

// Build DI container
$builder = new ContainerBuilder();
$container = $builder->addDefinitions(__DIR__ . '/../config/definitions.php')->build();
AppFactory::setContainer($container);

// Create Slim app
$app = AppFactory::create();

// Global middleware
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(new AddJsonResponseHeader());



// Public authentication routes (no JWT required)
$app->post('/auth/register', [\App\Controllers\AuthController::class, 'register']);
$app->post('/auth/login', [\App\Controllers\AuthController::class, 'login']);
$app->post('/auth/refresh', [\App\Controllers\AuthController::class, 'refresh']);
$app->post('/auth/logout', [\App\Controllers\AuthController::class, 'logout']);

// Simple root route (JSON response)
$app->get('/', function (Request $request, Response $response) {
    $payload = ['message' => 'Hello, World!'];
    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json');
});

// API routes – ensure JSON header and return controller response
$app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {
    $group->get('/products', function (Request $request, Response $response) use ($group) {
        $response = $group->getContainer()
            ->get(\App\Controllers\ProductsController::class)
            ->all($request, $response);
        return $response->withHeader('Content-Type', 'application/json');
    });
    $group->get('/products/{id:[0-9]+}', function (Request $request, Response $response, array $args) use ($group) {
        $response = $group->getContainer()
            ->get(\App\Controllers\ProductsController::class)
            ->byId($request, $response, $args);
        return $response->withHeader('Content-Type', 'application/json');
    });
    // POST, PUT, DELETE already return JSON via controller methods
    $group->post('/products', [\App\Controllers\ProductsController::class, 'create']);
    $group->put('/products/{id:[0-9]+}', [\App\Controllers\ProductsController::class, 'update']);
    $group->delete('/products/{id:[0-9]+}', [\App\Controllers\ProductsController::class, 'delete']);
})->add(new JwtMiddleware());

// Example protected route (outside /api) – also uses JWT middleware
$app->get('/me', function (Request $request, Response $response) {
    $user = $request->getAttribute('user');
    $payload = ['user' => $user];
    $response->getBody()->write(json_encode($payload));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
