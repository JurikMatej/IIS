<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ListApprovedAuctionsAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $auctions = $this->auctionRepository->findAllApproved();

        $this->logger->info("Approved auctions list was viewed.");

        $this->auctionViewRenderer->setLayout("index.php");
        
        $this->auctionViewRenderer->render($this->response,"show_all.php", ["auctions" => $auctions]);
        
        return $this->response;
    }
}