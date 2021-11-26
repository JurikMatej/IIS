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
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$dest");
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
        
        $this->auctionRepository->save($auction);

        $this->logger->info("Auction `${auction_id}` was edited.");

        $this->auctionViewRenderer->render($this->response,"send.php");
        
        return $this->response;
    }

}