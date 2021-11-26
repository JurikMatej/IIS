<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use \DateTime;

class DeleteAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        $auction_id = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auction_id);
        $user = $this->userRepository->findUserOfId($_SESSION['id']);

        // provides that auction can not be deleted from URL by any logged user
        if ($auction->getAuthorId() !== $_SESSION['id'] && $user->getRole() !== "Admin")
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }

        // running and not evaluated auction can not be deleted
        if ($auction->getDate() < new DateTime('now') && $auction->getApprover() !== null && $auction->getWinner() === null)
        {
            $dest = "/auction_run_error";
            header("Location: http://$name$port$dest");
            exit();   
        }
        
        $this->auctionRepository->delete($auction_id);

        $this->logger->info("Auction `${auction_id}` was deleted.");

        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        // redirect to home page
        header("Location: http://$name$port");
        exit();
        
        return $this->response;
    }

}