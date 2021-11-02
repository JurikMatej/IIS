<?php
declare(strict_types=1);

namespace App\Domain\DomainInterfaces;

/**
 * TODO Implement partial queries (no expansion of relationships)
 *		findCustomAuction($query) for querying any non-expanded auction
 *		findCustomBid($query) for querying any non-expanded bid
 *		findCustomUser($query) for querying any non-expanded user
 * 		Controller rendering example views
 */


interface DBRecordConstructable
{
    /**
     * @param array $dbRecords
     * @return array
     */
    public static function fromDbRecordArray(array $dbRecords): array;

    /**
     * @param object $dbRecord
     * @return object
     */
    public static function fromDbRecord(object $dbRecord);
}
