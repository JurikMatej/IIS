<?php


namespace App\Application\Actions\Home;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use App\Domain\Auction\AuctionRepository;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

abstract class HomePageAction extends Action
{

     /**
     * @var AuctionRepository
     */
    protected $auctionRepository;

    /**
     * @var PhpRenderer
     */
    protected $auctionViewRenderer;

    public function __construct(LoggerInterface $logger,
                                AuctionRepository $auctionRepository)
    {
        parent::__construct($logger);
        $this->auctionRepository = $auctionRepository;
        $this->auctionViewRenderer = new PhpRenderer("views");
    }   

}