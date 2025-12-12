<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Valitron\Validator;

$rootPath = realpath(__DIR__.'/..');
 
// Set Valitron (Spanish)
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

 
//  routes 
(require_once $rootPath.'/config/routes/web.php')($app);
(require_once $rootPath.'/config/routes/api.php')($app);

 

$app->run();
