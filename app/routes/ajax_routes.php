<?php
declare(strict_types=1);

use App\Application\Actions\Ajax\Auction\GetAllApprovedAuctionsAjaxAction;
use App\Application\Actions\Ajax\Auction\GetAllAuctionPendingUsersAjaxAction;
use App\Application\Actions\Ajax\Auction\GetAuctionWinningBidAjaxAction;
use App\Application\Actions\Ajax\Auction\GetAllPendingAuctionsAjaxAction;
use App\Application\Actions\Ajax\User\GetAllUsersAjaxAction;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
	$app->group('/ajax', function (Group $group) {
		// Auction resources
		$group->group('/auctions', function (Group $group) {
			$group->get('/approved', GetAllApprovedAuctionsAjaxAction::class);
			$group->get('/pending', GetAllPendingAuctionsAjaxAction::class);
			$group->get('/{id}/pending_users', GetAllAuctionPendingUsersAjaxAction::class);
			$group->get('/{id}/winning_bid', GetAuctionWinningBidAjaxAction::class);
		});

		// User resources
		$group->get('/users', GetAllUsersAjaxAction::class);
	});
};
