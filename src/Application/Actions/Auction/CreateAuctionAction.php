<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class CreateAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {

        if(!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$dest");
            exit();
        }

        $auctions = $this->auctionRepository->findAll();
        $rulesets = $this->auctionRepository->getAuctionRulesets();
        $types = $this->auctionRepository->getAuctionTypes();

        $this->logger->info("Auctions list was viewed.");

        $this->auctionViewRenderer->setLayout("index.php");
        
        $this->auctionViewRenderer->render($this->response,"create.php", ["auctions" => $auctions, "rulesets" => $rulesets, "types" => $types]);
        
        return $this->response;
    }

}