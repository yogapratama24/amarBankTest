<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Dotenv\Dotenv;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;


$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();

// Load .env file
$dotenv = Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

AppFactory::setContainer($container);

$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$app->post('/api/user', [App\Controllers\LoanController::class, 'create']);

$app->run();