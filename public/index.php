<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DI\ContainerBuilder;
use App\Middlewares\AddJsonResponseHeader;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

// tratar los argumentos de los handlers de ruta como un array
// $collectors = $app->getRouteCollector();
// $collectors->setDefaultInvocationStrategy(new \Slim\Handlers\Strategies\RequestResponseArgs );

// Body parsing middleware
$app->addBodyParsingMiddleware();

// error middleware 
$error_middleware = $app->addErrorMiddleware(true, true, true);

// evitar html en respuestas de error
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

// Middleware para agregar header JSON a todas las respuestas
$app->add(new AddJsonResponseHeader());

 
//  Establecer el idioma de forma estática
// (Esto es lo que Valitron usa para buscar los mensajes por defecto)
Valitron\Validator::lang('es');



$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello, World!");
    return $response;
});

$app->get('/api/products', App\Controllers\ProductsController::class . ':all');
$app->get('/api/products/{id:[0-9]+}',  App\Controllers\ProductsController::class . ':byId');
$app->post('/api/products', [App\Controllers\ProductsController::class,  'create']);
$app->put('/api/products/{id:[0-9]+}', [App\Controllers\ProductsController::class,  'update']);
$app->delete('/api/products/{id:[0-9]+}', [App\Controllers\ProductsController::class,  'delete']);



$app->run();
