<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use \DateTime;

class SendWaitingAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        if (!isset($_SESSION)) session_start();
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

        $this->logger->info("Auctions was approved.");

        $auction_id = (int) (isset($_SESSION['auction_id']))?$_SESSION['auction_id']:'';
        $date_string = (int)(isset($_POST['date']))?$_POST['date']:'';
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $date_string);
        

        $auction = $this->auctionRepository->findAuctionOfId($auction_id);
        $approver_id = (int) (isset($_SESSION['id']))?$_SESSION['id']:'';
        $approver = $this->userRepository->findUserOfId(intval($approver_id));
        
        $auction->setDate($date);
        $auction->setApprover($approver);
        $auction->setApproverId($approver_id);
        $auction->setAwaitingApproval(0);

        $this->auctionRepository->save($auction);

        if (isset($_SESSION['auction_id'])) unset($_SESSION['auction_id']);


        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        header("Location: http://$name$port/auctions/waiting");

        exit();
        
        return $this->response;
    }

}