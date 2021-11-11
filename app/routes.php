<?php
declare(strict_types=1);

use App\Application\Actions\Auction\ViewAuctionAction;
use App\Application\Actions\Bid\ListBidsAction;
use App\Application\Actions\Bid\ViewBidAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\EditUserAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\CheckUserAction;

use App\Application\Actions\Auction\ListAuctionsAction;

use Slim\Views\PhpRenderer;

// TODO Refer to https://github.com/slimphp/PHP-View to return or create views

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });


    // Template renderer add-on
    $renderer = new PhpRenderer("views");


    $app->get('/', function (Request $request, Response $response) use ($renderer) {
        // $response->getBody()->write('Hello world!');
        // return $response;
        return $renderer->render($response, "user/login.php", [
        ]);
    });
    

    $app->group('/users', function (Group $group) {
        $group->post('/check', CheckUserAction::class);
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->get('/{id}/edit', EditUserAction::class);
        $group->post('/{id}/update', UpdateUserAction::class);
    });


    $app->group('/auctions', function (Group $group) {
        $group->get('', ListAuctionsAction::class);
        $group->get('/{id}', ViewAuctionAction::class);
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
