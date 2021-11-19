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
        
        // TODO Updated DB table Bid and then proceed to generating view
        // $this->auctionViewRenderer->render($this->response,"register_user.php");
        
        return $this->response;
    }

}