<?php


namespace App\Application\Actions\Home;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class RenderHomeAction extends HomePageAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $auctions = $this->auctionRepository->findAll();

        $this->logger->info("List of auctions was fetched in order to generate home page view.");

        //$this->auctionViewRenderer->setLayout("index.php");
        
        $this->auctionViewRenderer->render($this->response,"home.php", ["auctions" => $auctions]);
        
        return $this->response;
    }

}