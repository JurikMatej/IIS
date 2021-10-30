<?php
declare(strict_types=1);


namespace App\Domain\DomainUtils;

use DateTime;

/**
 * Class DomainUtils
 * @package App\Domain\DomainUtils
 *
 * @brief Provides utilities for creating or any handling of Domain/Entity objects
 *
 * @todo Consider looking for something like Carbon\Time?? for Time representation instead of DateTime
 *       (Low priority - only if enough time is left)
 */
class DomainUtils
{
    private const DATE_TIME_FMT = "Y-m-d H:i:s";

    private const TIME_FMT = "H:i:s";


    /**
     * Create DateTime object from datetime returned by DB
     *
     * @param string|null $date
     * @return DateTime|null
     */
    public static function createDateTime(?string $date): ?DateTime
    {
        return $date !== null ?
            DateTime::createFromFormat(self::DATE_TIME_FMT, $date)
            : null;
    }

    /**
     * Create DateTime object from time returned by DB
     *
     * @param string|null $time
     * @return DateTime|null
     */
    public static function createTime(?string $time): ?DateTime
    {
        return $time !== null ?
            DateTime::createFromFormat(self::TIME_FMT, $time)
            : null;
    }


	/**
	 * @param string $photos
	 * @return string[]|null
	 */
	public static function parseAuctionPhotosRecord(string $photos): ?array
	{
		$photos_array = explode(',', $photos);
		return ($photos_array !== false) ?
			$photos_array
			: null;
	}
}
