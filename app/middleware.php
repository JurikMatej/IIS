<?php
declare(strict_types=1);

use App\Application\Middleware\NotFoundMiddleware;
use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);

	// HttpNotFoundException Middleware
	$app->add(NotFoundMiddleware::class);

//	$app->add(InternalErrorMiddleware::class);
};
