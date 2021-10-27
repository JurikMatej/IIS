<?php
declare(strict_types=1);


namespace App\Application\Actions\Bid;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ViewBidAction extends BidAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $bidId = (int) $this->resolveArg('id');
        $bid = $this->bidRepository->findBidOfId($bidId);

        $this->logger->info("Bid of id `${bidId}` was viewed.");

        return $this->respondWithData($bid);
    }
}