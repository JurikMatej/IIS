<?php
declare(strict_types=1);


namespace App\Application\Actions\Ajax\Auction;


use App\Application\Actions\Ajax\Auction\AuctionAjaxAction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetAllAuctionPendingUsersAjaxAction extends AuctionAjaxAction
{

	/**
	 * @inheritDoc
	 */
	protected function action(): Response
	{
		$related_auction_id = (int) $this->resolveArg('id');
		$related_auction = $this->auctionRepository->findAuctionOfId($related_auction_id);
		$related_auction_bids_with_pending_users = $this->bidRepository
				->findAllWaitingUsers($related_auction->getId());

		$this->logger->info("List of all auction of id $related_auction_id's pending 
							users was fetched.");

		// Extract only pending users from fetched bids
		$related_auction_pending_users = [];
		foreach ($related_auction_bids_with_pending_users as $bid)
		{
			// Format response - add needed ajax data (related auction & bid IDs)
			$user = $bid->getUser()->jsonSerialize();
			$user["related_bid"] = $bid->getId();
			$user["related_auction"] = $related_auction_id;

			$related_auction_pending_users[] = $user;
		}

		return $this->respondWithData($related_auction_pending_users);
	}
}
