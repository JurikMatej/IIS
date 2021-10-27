<?php
declare(strict_types=1);

namespace App\Domain\DomainInterfaces;


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
