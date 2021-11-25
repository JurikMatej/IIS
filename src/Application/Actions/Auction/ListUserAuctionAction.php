<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use \DateTime;

class ListUserAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        if(!isset($_SESSION)) session_start();
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        $user_id = (int) $_SESSION['id'];

        $user_auctions = $this->auctionRepository->getAuctionsOfUserID($user_id);
        $approver_auctions = $this->auctionRepository->getAuctionsOfApproverID($user_id);
        $user_bids = $this->bidRepository->findAllUserBids($user_id);
        $auctions_where_user_bid = [];
        foreach ($user_bids as $user_bid)
        {
            $_auction = $this->auctionRepository->findAuctionOfId($user_bid->getAuctionId());
            array_push($auctions_where_user_bid, $_auction);
        }


        $this->logger->info("List of user's auctions was showed.");

        $this->auctionViewRenderer->setLayout("index.php");
        
        $this->auctionViewRenderer->render($this->response,"user_auctions.php", 
        ["user_auctions" => $user_auctions, "approver_auctions" => $approver_auctions, "auctions_where_user_bid" => $auctions_where_user_bid]);

        return $this->response;
    }

}