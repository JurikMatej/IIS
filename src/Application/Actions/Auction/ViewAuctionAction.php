<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ViewAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $auctionId = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);

        $this->logger->info("User of id `${auctionId}` was viewed.");

        return $this->respondWithData($auction);
    }
}