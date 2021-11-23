<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;


class BidInAuctionAction extends AuctionAction
{

    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $script = $_SERVER["PHP_SELF"];
            if (strpos($dest, '/') === 0) { // absolute path
                $path = $dest;
            } else {
                $path = substr($script, 0,
                strrPos($script, "/"))."/$dest";
            }
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$path");
            exit();
        }

        $this->logger->info("Bid was placed.");
        $auction_id = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auction_id);
        $highest_bid = $this->bidRepository->findHighestAuctionBid($auction_id);
        $user_id = isset($_SESSION['id'])?$_SESSION['id']:''; 
        $value = (int)(isset($_POST['value']))?$_POST['value']:'';

        $value_failed = false;
        $starting_failed = false;
        $increase_failed = false;

        if ($auction->getType() === "ascending-bid")
        {
            if ($value < $auction->getStartingBid())
            {
                $starting_failed = true;
            }
            // if closed do not check highest bid
            else if (($value <= $highest_bid->getValue() && $auction->getRuleset() !== "closed"))
            {
                $value_failed = true;
            }
            else if ($highest_bid->getValue() + $auction->getMinimumBidIncrease() > $value )
            {
                $increase_failed = true;
            }
            else
            {
                $bid = $this->bidRepository->findBidByAuctionAndUserId($auction_id, $user_id);
                $bid->setValue($value);
                $this->bidRepository->save($bid);
            }
        }
        else //descending-bid
        {
            if ($value > $auction->getStartingBid())
            {
                $starting_failed = true;
            }
            else if (($value >= $highest_bid->getValue() && $highest_bid->getValue() !== 0 && $auction->getRuleset() !== "closed"))
            {
                $value_failed = true;
            }
            else
            {
                $bid = $this->bidRepository->findBidByAuctionAndUserId($auction_id, $user_id);
                $bid->setValue($value);
                $this->bidRepository->save($bid);
            }
        }

        $this->auctionViewRenderer->render($this->response,"update_bid.php",
        ['auction_id' => $auction_id, 'value_failed' => $value_failed, 
        'starting_failed' => $starting_failed, 'increase_failed' => $increase_failed]);
        
        return $this->response;
    }

}