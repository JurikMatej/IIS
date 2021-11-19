<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class RejectUserInAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $bidId = (int) $this->resolveArg('bidId');
        $bid = $this->bidRepository->findBidOfId($bidId);
        $auctionId = $bid->getAuctionId();
        $this->bidRepository->delete($bidId);

        $this->auctionViewRenderer->setLayout("index.php");
        $this->auctionViewRenderer->render($this->response,"reject_approve.php", ["auctionId" => $auctionId]);
        
        return $this->response;
    }
}