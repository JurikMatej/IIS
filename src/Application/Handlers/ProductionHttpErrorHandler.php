<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Actions\Action;
use App\Application\Actions\ActionError;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Views\PhpRenderer;

class ProductionHttpErrorHandler extends SlimErrorHandler
{

	/**
	 * @inheritdoc
	 */
	protected function respond(): Response
	{
		// Treat any exception as Internal Server Error type
		$exception = $this->exception;
		$statusCode = 500;
		$error = new ActionError(
			ActionError::SERVER_ERROR,
			'An internal error has occurred while processing your request.'
		);

		// Check for more specific (HTTP) exceptions
		if ($exception instanceof HttpException) {
			$statusCode = $exception->getCode();
			$error->setDescription($exception->getMessage());

			if ($exception instanceof HttpNotFoundException) {
				$error->setType(ActionError::RESOURCE_NOT_FOUND);
			}
			else // If not Server nor Not Found error - tell the user that "something" went wrong
			{
				$error->setType(ActionError::NOT_IMPLEMENTED);
			}
		}

		$errorType = $error->getType();

		if ($errorType === ActionError::SERVER_ERROR)
			$template = "500.php";
		elseif ($errorType === ActionError::RESOURCE_NOT_FOUND)
			$template = "404.php";
		else
			$template = "generic.php";

		$response = $this->responseFactory->createResponse($statusCode);
		$renderer = new PhpRenderer("views");
		return $renderer->render($response, "error/$template", []);
	}
}
