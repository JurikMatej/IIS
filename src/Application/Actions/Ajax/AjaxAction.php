<?php


namespace App\Application\Actions\Ajax;

use App\Domain\Auction\AuctionRepository;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;


abstract class AjaxAction extends Action
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