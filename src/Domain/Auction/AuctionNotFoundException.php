<?php
declare(strict_types=1);

namespace App\Domain\Auction;

use App\Domain\DomainException\DomainRecordNotFoundException;

class AuctionNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The auction you requested does not exist.';
}