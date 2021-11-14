<?php
declare(strict_types=1);


namespace App\Domain\AuctionPhoto;


use App\Domain\DomainInterfaces\DBRecordConstructable;
use Exception;
use JsonSerializable;

/**
 * @brief Composition entity that falls under Auction entities
 *          (Does not have its own repository)
 */
class AuctionPhoto implements JsonSerializable
{
	/**
	 * @var int|null
	 */
	private $id;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var int|null
	 */
	private $auction_id;


	/**
	 * @brief AuctionPhoto constructor - private to ensure creation of Auction objects
	 *                                 through static factory methods
	 */
	private function __construct()
	{
	}


	/**
	 * @brief Static parameterless factory
	 * @return AuctionPhoto
	 */
	public static function create(): AuctionPhoto
	{
		return new self();
	}


	/**
	 * @param object $auctionRecord
	 * @return AuctionPhoto[]
	 */
	public static function fromAuctionDbRecord(object $auctionRecord): array
	{
		$result = [];
		$photoAuctionID = $auctionRecord->id;

		if ($auctionRecord->auction_ids !== null && $auctionRecord->auction_photos )
		{
			$photoIDs = explode(",", $auctionRecord->auction_ids);
			$photoIDsCount = count($photoIDs);

			$photoPaths = explode(",", $auctionRecord->auction_photos);
			$photoPathsCount = count($photoPaths);

			for ($photoIdx = 0; $photoIdx < min($photoIDsCount, $photoPathsCount); $photoIdx++) {
				$result[] = self::create()
					->setId((int)$photoIDs[$photoIdx])
					->setPath($photoPaths[$photoIdx])
					->setAuctionId((int)$photoAuctionID);
			}
		}

		return $result;
	}


	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			"id" => $this->id,
			"path" => $this->path,
			"auction_id" => $this->auction_id
		];
	}


	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param int|null $id
	 * @return AuctionPhoto
	 */
	public function setId(?int $id): AuctionPhoto
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 * @return AuctionPhoto
	 */
	public function setPath(string $path): AuctionPhoto
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAuctionId(): int
	{
		return $this->auction_id;
	}

	/**
	 * @param ?int $auction_id
	 * @return AuctionPhoto
	 */
	public function setAuctionId(?int $auction_id): AuctionPhoto
	{
		$this->auction_id = $auction_id;
		return $this;
	}
}
