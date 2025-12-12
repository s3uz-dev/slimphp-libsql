<?php

declare(strict_types=1);

use Slim\App; 
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
 

return function (App $app) {
 
$app->get('/', function (Request $request, Response $response) {
    $payload = ['message' => 'Hello, World!'];
    $response->getBody()->write(json_encode($payload));
    return $response;
});

};
