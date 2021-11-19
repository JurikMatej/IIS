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
        session_start();
        $role = isset($_SESSION['role'])? $_SESSION['role'] : '';
        $auctionId = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);
        $registred = $this->bidRepository->findAllRegistredUsers($auction->getId());
        $waiting = $this->bidRepository->findAllWaitingUsers($auction->getId());

        if (!isset($_SESSION['id']) || 
        !(($role === "Auctioneer" && $auction->getApproverId() === $_SESSION['id']) || $role === "Admin")) 
        // only concrete Approver can access 
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

        $this->logger->info("Auction of id `${auctionId}` with Users was viewed.");

        $this->auctionViewRenderer->setLayout("index.php");
        $this->auctionViewRenderer->render($this->response, "users.php", 
            ["auction" => $auction, "registred" => $registred, "waiting" => $waiting]);
        
        return $this->response;
    }
}