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

        $auctions = $this->auctionRepository->findAll();
        $rulesets = $this->auctionRepository->getAuctionRulesets();
        $types = $this->auctionRepository->getAuctionTypes();

        $this->logger->info("Auctions list was viewed.");

        $this->auctionViewRenderer->setLayout("index.php");
        
        $this->auctionViewRenderer->render($this->response,"create.php", ["auctions" => $auctions, "rulesets" => $rulesets, "types" => $types]);
        
        return $this->response;
    }

}