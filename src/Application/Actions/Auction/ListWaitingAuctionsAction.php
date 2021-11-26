<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ListWaitingAuctionsAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        if (!isset($_SESSION)) session_start();
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        if ($_SESSION['role'] !== "Admin" && $_SESSION['role'] !== "Auctioneer")
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }
        
        $auctions = $this->auctionRepository->findAllWaitingForApproval();

        $this->logger->info("Approved auctions list was viewed.");

        $this->auctionViewRenderer->setLayout("index.php");
        
        $this->auctionViewRenderer->render($this->response,"show_all_waiting.php", ["auctions" => $auctions]);
        
        return $this->response;
    }
}