<?php
declare(strict_types=1);

use App\Application\Actions\Ajax\Auction\GetAllApprovedAuctionsAjaxAction;
use App\Application\Actions\Ajax\Auction\GetAllPendingAuctionsAjaxAction;
use App\Application\Actions\Ajax\User\GetAllUsersAjaxAction;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Refer to https://github.com/slimphp/PHP-View to return or create views

return function (App $app) {
	$app->group('/ajax', function (Group $group)    {
		// Auction resources
		$group->group('/auctions', function (Group $group) {
			$group->get('/approved', GetAllApprovedAuctionsAjaxAction::class);
			$group->get('/pending', GetAllPendingAuctionsAjaxAction::class);
		});

		// User resources
		$group->get('/users', GetAllUsersAjaxAction::class);
	});
};
