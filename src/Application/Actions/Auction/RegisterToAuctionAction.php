<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use \DateTime;

class RegisterToAuctionAction extends AuctionAction
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

        $auctionId = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);
        $is_registred = $this->bidRepository->registrationExists($auctionId, $_SESSION['id']);

        // author and approver can not obtain acces to registration from URL
        if ($auction->getAuthorId() === $_SESSION['id'] || $auction->getApproverId() === $_SESSION['id'])
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }

        $finished = false;
        if ($auction->getTimeLimit() === null) {
            if ($auction->getWinner() !== null)
            {
                $finished = true;
            }
        }
        else {
            $date = $auction->getDate();
            $time_limit = $auction->getTimeLimit();
            if ($date->add($time_limit) < new DateTime('now'))
            {
                $finished = true;
            }
        }
        // can not register on finished auction from URL
        if ($finished || $is_registred) {
            $dest = "/auction_register_error";
            header("Location: http://$name$port$dest");
            exit();
        }

        $this->bidRepository->registerUser($auction->getId(), $_SESSION['id']);

        $this->auctionViewRenderer->render($this->response, "register.php", ["auction_id" => $auctionId]);
    
        return $this->response;
    }

}