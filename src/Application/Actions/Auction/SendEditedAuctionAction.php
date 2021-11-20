<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use \DateTime;

class SendEditedAuctionAction extends AuctionAction
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

        $rulesets = $this->auctionRepository->getAuctionRulesets();
        $types = $this->auctionRepository->getAuctionTypes();

        $auction_id = (int) $this->resolveArg('id');
        $auction = $this->auctionRepository->findAuctionOfId($auction_id);


        $hours = (int)(isset($_POST['hours']))?$_POST['hours']:'0';
        $minutes = (int)(isset($_POST['minutes']))?$_POST['minutes']:'0';

        if ($hours  ===  '0' && $minutes === '0')
        {
            $time_limit = null;
        }
        else
        {
            $time_limit = new DateTime();
            $time_limit->setTime($hours, $minutes);
        }
       
        $name = (isset($_POST['name']))?$_POST['name']:'';
        $description = (isset($_POST['description']))?$_POST['description']:'';
        $starting_bid =( isset($_POST['starting_bid']))?$_POST['starting_bid']:'';
        $minimum_bid_increase = (isset($_POST['minimum_bid_increase']))?$_POST['minimum_bid_increase']:'';
        $ruleset = (isset($_POST['ruleset']))?$_POST['ruleset']:'';
        $type = (isset($_POST['type']))?$_POST['type']:'';
        $biding_minutes = (int)(isset($_POST['biding_minutes']))?$_POST['biding_minutes']:'0';
        if ($biding_minutes === '0')
        {
            $bidding_interval = null;
        }
        else
        {
            $bidding_interval= new DateTime();
            $bidding_interval->setTime(0, $biding_minutes);
        }
        
        $photos = [];//TODO


        $typeid = 1;
        foreach ($types as $type)
        {
            if ($type->type == $_POST["type"])
            {
                $typeid = $type->id;
            }
        }
        $rulesetid = 1;
        foreach ($rulesets as $ruleset)
        {
            if ($ruleset->ruleset == $_POST["ruleset"])
            {
                $rulesetid = $ruleset->id;
            }
        }

        $auction->setTimeLimit($time_limit);
        $auction->setName($name);
        $auction->setDescription($description);
        $auction->setStartingBid($starting_bid);
        $auction->setMinimumBidIncrease($minimum_bid_increase);
        $auction->setRuleset($ruleset->ruleset);
        $auction->setRulesetId($rulesetid);
        $auction->setType($type->type);
        $auction->setTypeId($typeid);
        $auction->setBiddingInterval($bidding_interval);
        $auction->setPhotos($photos);
        
        $this->auctionRepository->save($auction);

        $this->logger->info("Auction `${auction_id}` was edited.");

        $this->auctionViewRenderer->render($this->response,"send.php");
        
        return $this->response;
    }

}