<?php
declare(strict_types=1);


namespace App\Domain\Bid;

use App\Domain\DomainException\DomainRecordNotFoundException;

class BidNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The bid you requested does not exist.';
}