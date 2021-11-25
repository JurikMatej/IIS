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
        if(!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$dest");
            exit();
        }

        $auctionId = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);
        $bids = $this->bidRepository->findAllAuctionBids($auction->getId());

        $this->logger->info("Auction of id `${auctionId}` was viewed.");

        $this->auctionViewRenderer->setLayout("index.php");
        $is_registred = $this->bidRepository->registrationExists($auctionId, $_SESSION['id']);
        $is_approved = false;
        if ($is_registred)
        {
            $bid = $this->bidRepository->findBidByAuctionAndUserId($auctionId, $_SESSION['id']);
            if ($bid != null) $is_approved = !$bid->getAwaitingApproval();
        }
        $this->auctionViewRenderer->render($this->response, "show.php", 
        ["auction" => $auction, "bids" => $bids, "is_registred" => $is_registred, 'is_approved' => $is_approved]);
        
        return $this->response;
    }
}