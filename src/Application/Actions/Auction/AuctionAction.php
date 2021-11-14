<?php


namespace App\Application\Actions\Auction;


use App\Domain\Auction\AuctionRepository;
use App\Domain\Bid\BidRepository;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Infrastructure\Persistence\Auction\RemoteAuctionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use App\Application\Actions\Action;
use Slim\Views\PhpRenderer;

abstract class AuctionAction extends Action
{
    /**
     * @var AuctionRepository
     */
    protected $auctionRepository;

    /**
     * @var AuctionRepository
     */
    protected $bidRepository;

    /**
     * @var PhpRenderer
     */
    protected $auctionViewRenderer;

    public function __construct(LoggerInterface $logger,
                                AuctionRepository $auctionRepository,
                                BidRepository $bidRepository)
    {
        parent::__construct($logger);
        $this->auctionRepository = $auctionRepository;
        $this->bidRepository = $bidRepository;
        $this->auctionViewRenderer = new PhpRenderer("views/auction");
    }
}