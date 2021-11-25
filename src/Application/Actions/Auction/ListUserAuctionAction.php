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
        session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $script = $_SERVER["PHP_SELF"];
            if (strpos($dest, '/') === 0) { // absolute path
                $path = $dest;
            } else {
                $path = substr($script, 0,
                strrPos($script, "/"))."/$dest";
            }
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$path");
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