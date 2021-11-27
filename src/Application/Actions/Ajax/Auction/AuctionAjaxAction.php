<?php


namespace App\Application\Actions\Ajax\Auction;

use App\Domain\Auction\AuctionRepository;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;


abstract class AuctionAjaxAction extends Action
{
     /**
     * @var AuctionRepository
     */
    protected $auctionRepository;


    public function __construct(LoggerInterface $logger,
                                AuctionRepository $auctionRepository)
    {
        parent::__construct($logger);
        $this->auctionRepository = $auctionRepository;
    }   

}