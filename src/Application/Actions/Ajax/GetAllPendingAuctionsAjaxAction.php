<?php
declare(strict_types=1);


namespace App\Application\Actions\Ajax;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetAllPendingAuctionsAjaxAction extends AjaxAction
{

	/**
	 * @inheritDoc
	 */
	protected function action(): Response
	{
		return $this->respondWithData("");
	}
}