<?php
declare(strict_types=1);

use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Refer to https://github.com/slimphp/PHP-View to return or create views

return function (App $app) {
	// Fallback 404 route
	$app->map(
		['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
		'/{routes:.+}',
		function(Request $request, Response $response)
		{
			throw new HttpNotFoundException($request);
		});
};
