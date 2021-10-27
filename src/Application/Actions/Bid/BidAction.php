<?php
declare(strict_types=1);


namespace App\Application\Actions\Bid;


use App\Application\Actions\Action;
use App\Domain\Bid\BidRepository;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

abstract class BidAction extends Action
{
    /**
     * @var BidRepository
     */
    protected $bidRepository;

    /**
     * BidAction constructor.
     * @param LoggerInterface $logger
     * @param BidRepository $bidRepository
     */
    public function __construct(LoggerInterface $logger,
                                BidRepository $bidRepository)
    {
        parent::__construct($logger);
        $this->bidRepository = $bidRepository;
    }


}