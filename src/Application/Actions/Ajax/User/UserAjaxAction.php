<?php


namespace App\Application\Actions\Ajax\User;

use App\Domain\Auction\AuctionRepository;
use App\Application\Actions\Action;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;


abstract class UserAjaxAction extends Action
{
     /**
     * @var UserRepository
     */
    protected $userRepository;


    public function __construct(LoggerInterface   $logger,
                                UserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
    }   

}