<?php


namespace App\Application\Actions\Auction;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class WinnerAuctionAction extends AuctionAction
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
        $winnerId = (int) $this->resolveArg('winnerId');
        $auction = $this->auctionRepository->findAuctionOfId($auctionId);
        $winner = $this->userRepository->findUserOfId($winnerId);
        $auction->setWinnerId($winnerId);
        $auction->setWinner($winner);
        $this->auctionRepository->save($auction);


        $this->auctionViewRenderer->setLayout("index.php");

        // same redirect as register.php
        $this->auctionViewRenderer->render($this->response, "register.php", ["auction_id" => $auctionId]);
        
        return $this->response;
    }
}