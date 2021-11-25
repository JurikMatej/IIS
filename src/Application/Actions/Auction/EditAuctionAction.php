<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use \DateTime;

class EditAuctionAction extends AuctionAction
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

        $auction_id = (int) $this->resolveArg('id');
        
        $auction = $this->auctionRepository->findAuctionOfId($auction_id);

        if ($auction->getDate() < new DateTime('now') && $auction->getApprover() !== null)
        {
            $dest = "/auction_edit_error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        $rulesets = $this->auctionRepository->getAuctionRulesets();
        $types = $this->auctionRepository->getAuctionTypes();

        $this->logger->info("Auction `${auction_id}` was edited.");

        $this->auctionViewRenderer->setLayout("index.php");

        $this->auctionViewRenderer->render($this->response,"edit.php", ["auction" => $auction, "rulesets" => $rulesets, "types" => $types]);

        return $this->response;
    }

}