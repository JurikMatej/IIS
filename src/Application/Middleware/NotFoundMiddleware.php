<?php
declare(strict_types=1);


namespace App\Application\Middleware;

use http\Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\PhpRenderer;

class NotFoundMiddleware implements Middleware
{

	private static $renderer;

	/**
	 * @inheritDoc
	 *
	 * @throws HttpInternalServerErrorException
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		try
		{
			return $handler->handle($request);
		}
		catch (HttpNotFoundException $httpException)
		{
			// Create new response
			$response = (new Psr7Response())->withStatus(404);
			self::$renderer = new PhpRenderer("views");

			// Redirect to 404 page
			try
			{
				return self::$renderer->render($response, 'error/404.php', []);
			} catch (\Throwable $e) {
				throw new HttpInternalServerErrorException($request);
			}
		}
	}
}
