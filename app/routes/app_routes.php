<?php
declare(strict_types=1);

use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\EditUserAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\CheckUserAction;
use App\Application\Actions\User\RegisterUserAction;
use App\Application\Actions\User\DeleteUserAction;
use App\Application\Actions\User\LogoutUserAction;
use App\Application\Actions\Home\RenderHomeAction;

use App\Application\Actions\Auction\ViewAuctionAction;
use App\Application\Actions\Auction\ListApprovedAuctionsAction;
use App\Application\Actions\Auction\ListWaitingAuctionsAction;
use App\Application\Actions\Auction\CreateAuctionAction;
use App\Application\Actions\Auction\SendAuctionAction;
use App\Application\Actions\Auction\ViewWaitingAuctionAction;
use App\Application\Actions\Auction\SendWaitingAuctionAction;
use App\Application\Actions\Auction\RegisterToAuctionAction;
use App\Application\Actions\Auction\EditAuctionAction;
use App\Application\Actions\Auction\SendEditedAuctionAction;
use App\Application\Actions\Auction\DeleteAuctionAction;
use App\Application\Actions\Auction\ListUserAuctionAction;
use App\Application\Actions\Auction\UsersInAuctionAction;
use App\Application\Actions\Auction\RejectUserInAuctionAction;
use App\Application\Actions\Auction\ApproveUserInAuctionAction;
use App\Application\Actions\Auction\BidInAuctionAction;
use App\Application\Actions\Auction\WinnerAuctionAction;

use App\Application\Actions\Bid\ListBidsAction;
use App\Application\Actions\Bid\ViewBidAction;

use Slim\Views\PhpRenderer;

// Refer to https://github.com/slimphp/PHP-View to return or create views

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // Template renderer add-on
    $renderer = new PhpRenderer("views");

    $app->group('/', function (Group $group)    {
        $group->get('', RenderHomeAction::class);
        $group->get('logout', LogoutUserAction::class);
    });


    $app->get('/login', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, "user/login.php", [
        ]);
    });


    $app->get('/register', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, "user/create.php", [
        ]);
    });

    
    $app->get('/error', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, "error/login_error.php", [
        ]);
    });

    $app->get('/unauthorized_access_error', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, "error/unauthorized_access_error.php", [
        ]);
    });

    $app->get('/auction_run_error', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, "error/auction_run_error.php", [
        ]);
    });

    $app->get('/auction_register_error', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, "error/auction_register_error.php", [
        ]);
    });

    $app->get('/auction_edit_error', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, "error/auction_edit_error.php", [
        ]);
    });
    

    $app->group('/users', function (Group $group) {
        $group->post('/register', RegisterUserAction::class);
        $group->post('/check', CheckUserAction::class);
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->get('/{id}/edit', EditUserAction::class);
        $group->post('/{id}/update', UpdateUserAction::class);
        $group->get('/{id}/delete', DeleteUserAction::class);
    });


    $app->group('/auctions', function (Group $group) {
        $group->get('/create', CreateAuctionAction::class);
        $group->post('/send', SendAuctionAction::class);
        $group->post('/update', SendWaitingAuctionAction::class);
        $group->get('/user_auctions', ListUserAuctionAction::class);
        $group->get('', ListApprovedAuctionsAction::class);
        $group->get('/waiting', ListWaitingAuctionsAction::class);
        $group->get('/waiting/{id}', ViewWaitingAuctionAction::class);
        $group->get('/{id}', ViewAuctionAction::class);
        $group->get('/{id}/winner/{winnerId}', WinnerAuctionAction::class);
        $group->get('/{id}/register', RegisterToAuctionAction::class);
        $group->get('/{id}/edit', EditAuctionAction::class);
        $group->post('/{id}/send-edit', SendEditedAuctionAction::class);
        $group->get('/{id}/delete', DeleteAuctionAction::class);
        $group->get('/{id}/users', UsersInAuctionAction::class);
        $group->post('/{id}/bid', BidInAuctionAction::class);
        $group->get('/{id}/users/{bidId}/reject', RejectUserInAuctionAction::class);
        $group->get('/{id}/users/{bidId}/approve', ApproveUserInAuctionAction::class);
    });


    // DEBUG ONLY GROUP
    if ($_ENV['APP_DEBUG'])
    {
        $app->group('/bids', function (Group $group) {
            $group->get('', ListBidsAction::class);
            $group->get('/{id}', ViewBidAction::class);
        });
    }
};
