<?php


namespace App\Application\Actions\Ajax\Auction;

use Psr\Http\Message\ResponseInterface as Response;

class GetAllApprovedAuctionsAjaxAction extends AuctionAjaxAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $auctions = $this->auctionRepository->findAllApproved();

        $this->logger->info("List of all approved auctions was fetched.");

        return $this->respondWithData($auctions);
    }

}