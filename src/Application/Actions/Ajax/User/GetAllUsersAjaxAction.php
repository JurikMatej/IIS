<?php
declare(strict_types=1);


namespace App\Application\Actions\Ajax\User;

use App\Application\Actions\Ajax\User\UserAjaxAction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;


class GetAllUsersAjaxAction extends UserAjaxAction
{

	/**
	 * @inheritDoc
	 */
	protected function action(): Response
	{
		$users = $this->userRepository->findAll();

		$this->logger->info("List of all users was fetched.");

		return $this->respondWithData($users);
	}
}
