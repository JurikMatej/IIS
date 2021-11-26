<?php
declare(strict_types=1);

use App\Application\Actions\Ajax\GetAllPendingAuctionsAjaxAction;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Ajax\GetAllApprovedAuctionsAjaxAction;

// Refer to https://github.com/slimphp/PHP-View to return or create views

return function (App $app) {
	$app->group('/ajax', function (Group $group)    {
		$group->get('/auctions/approved', GetAllApprovedAuctionsAjaxAction::class);
		$group->get('/auctions/pending', GetAllPendingAuctionsAjaxAction::class);
	});
};
