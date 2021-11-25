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

        $this->logger->info("List of user's auctions was showed.");

        $this->auctionViewRenderer->setLayout("index.php");
        
        $this->auctionViewRenderer->render($this->response,"user_auctions.php", ["user_auctions" => $user_auctions, "approver_auctions" => $approver_auctions]);

        return $this->response;
    }

}