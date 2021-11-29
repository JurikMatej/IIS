<?php
declare(strict_types=1);

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ProductionHttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Set execution time to half an hour - ajax functionality time limit
ini_set('max_execution_time', "1800");

/** Register 3rd party libs */
if (!isset($_ENV['APP_DEBUG'])) { // Load env from file only if env was not already supplied
	$dotenv = Dotenv::createImmutable(__DIR__ . '/..', '.env');
	$dotenv->load();
	$dotenv->required(['DB_DSN', 'DB_USER'])->notEmpty();
	$dotenv->required(['APP_DEBUG', 'APP_DISPLAY_ERROR_DETAILS'])->notEmpty()->isBoolean();
	$dotenv->required(['DB_PASS']);
}


/** CREATE APP CONFIG */
// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

$debug_mode = (bool) $_ENV['APP_DEBUG'];
if (!$debug_mode) {
	$containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();


/** CREATE CORE APP */
// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();


/** Build app object fields */
// Register middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Register application routes
$app_routes = require __DIR__ . '/../app/routes/app_routes.php';
$app_routes($app);

// Register ajax routes
$ajax_routes = require __DIR__ . '/../app/routes/ajax_routes.php';
$ajax_routes($app);

// Register fallback routes
$fallback_routes = require __DIR__ . '/../app/routes/fallback_routes.php';
$fallback_routes($app);



/** @var SettingsInterface $settings */
$settings = $container->get(SettingsInterface::class);

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();



// Create Error Handler
$responseFactory = $app->getResponseFactory();


if ($debug_mode)
{
	$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
}
else
{
	$errorHandler = new ProductionHttpErrorHandler($callableResolver, $responseFactory);
}



// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);




// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);



// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
