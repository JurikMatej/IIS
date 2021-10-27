<?php
declare(strict_types=1);


namespace App\Application\Actions\Bid;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ListBidsAction extends BidAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $bids = $this->bidRepository->findAll();

        $this->logger->info("Bids list was viewed.");

        return $this->respondWithData($bids);
    }
}