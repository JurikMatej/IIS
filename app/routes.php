<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
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
        return $renderer->render($response, "user/index.php", [
        ]);
    });
    

    // TODO example of Action/Controller usage
    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });


    // TODO ANY NEW ROUTES BELONG HERE
    // SEPARATE WITH LOGICAL WHITESPACE TO CREATE MNEMONIC GROUPINGS OF ROUTES (hlavne micko!!!)
};
