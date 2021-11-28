<?php


namespace App\Application\Actions\Ajax\Auction;

use App\Domain\Auction\AuctionRepository;
use App\Application\Actions\Action;
use App\Domain\Bid\BidRepository;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;


abstract class AuctionAjaxAction extends Action
{
     /**
     * @var AuctionRepository
     */
    protected $auctionRepository;

	/**
	 * @var UserRepository
	 */
	protected $userRepository;

	/**
	 * @var BidRepository
	 */
	protected $bidRepository;


    public function __construct(LoggerInterface $logger,
                                AuctionRepository $auctionRepository,
								UserRepository $userRepository,
								BidRepository $bidRepository)
    {
        parent::__construct($logger);
        $this->auctionRepository = $auctionRepository;
		$this->userRepository = $userRepository;
		$this->bidRepository = $bidRepository;
    }

}