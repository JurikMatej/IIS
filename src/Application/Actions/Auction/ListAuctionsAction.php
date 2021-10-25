<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ListAuctionsAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $auctions = $this->auctionRepository->findAll();

        $this->logger->info("Auctions list was viewed.");

        return $this->respondWithData($auctions);
    }
}