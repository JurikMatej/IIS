<?php
declare(strict_types=1);


namespace App\Application\Actions\Ajax\Auction;


use App\Application\Actions\Ajax\Auction\AuctionAjaxAction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class GetAuctionWinningBidAjaxAction extends AuctionAjaxAction
{

	/**
	 * @inheritDoc
	 */
	protected function action(): Response
	{
		$related_auction_id = (int) $this->resolveArg('id');
		$related_auction = $this->auctionRepository->findAuctionOfId($related_auction_id);

		if ($related_auction->getType() === "ascending-bid")
		{
			$winning_bid = $this->bidRepository->findHighestAuctionBid($related_auction_id);
		}
		else
		{
			$winning_bid = $this->bidRepository->findLowestAuctionBid($related_auction_id);
		}

		$this->logger->info("The winning bid of auction with id $related_auction_id was fetched.");


		return $this->respondWithData($winning_bid ?? null);
	}
}
