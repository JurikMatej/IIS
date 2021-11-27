<?php
declare(strict_types=1);


namespace App\Application\Actions\Ajax\Auction;


use App\Application\Actions\Ajax\Auction\AuctionAjaxAction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetAllPendingAuctionsAjaxAction extends AuctionAjaxAction
{

	/**
	 * @inheritDoc
	 */
	protected function action(): Response
	{
		$pending_auctions = $this->auctionRepository->findAllWaitingForApproval();

		$this->logger->info("List of all pending auctions was fetched.");

		return $this->respondWithData($pending_auctions);
	}
}
