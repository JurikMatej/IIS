<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UsersInAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        if(!isset($_SESSION)) session_start();
        $role = isset($_SESSION['role'])? $_SESSION['role'] : '';
        $auctionId = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);
        $registred = $this->bidRepository->findAllRegistredUsers($auction->getId());
        $waiting = $this->bidRepository->findAllWaitingUsers($auction->getId());
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        if (!isset($_SESSION['id'])) {
            $dest = "/error";
            header("Location: http://$name$port$dest");
            exit();
        } 
        else if (!(($role === "Auctioneer" && $auction->getApproverId() === $_SESSION['id']) || $role === "Admin")) 
        // only concrete Approver can access 
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }

        $this->logger->info("Auction of id `${auctionId}` with Users was viewed.");

        if($auction->getType() === "ascending-bid")
        {
            $highest_lowest_bid = $this->bidRepository->findHighestAuctionBid($auctionId);
        }
        else
        {
            $highest_lowest_bid = $this->bidRepository->findLowestAuctionBid($auctionId);
        }

        $this->auctionViewRenderer->setLayout("index.php");
        $this->auctionViewRenderer->render($this->response, "users.php", 
            ["auction" => $auction, "registred" => $registred, "waiting" => $waiting, 'highest_lowest_bid' => $highest_lowest_bid]);
        
        return $this->response;
    }
}