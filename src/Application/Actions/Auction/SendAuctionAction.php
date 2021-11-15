<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

use \DateTime;

class SendAuctionAction extends AuctionAction
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

        $this->logger->info("Auctions was created.");
        $rulesets = $this->auctionRepository->getAuctionRulesets();
        $types = $this->auctionRepository->getAuctionTypes();

        $author_id = (int) (isset($_SESSION['id']))?$_SESSION['id']:'';
        $author = $this->userRepository->findUserOfId(intval($author_id));

        $date_string = (isset($_POST['date']))?$_POST['date']:'';
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $date_string);
        $name = (isset($_POST['name']))?$_POST['name']:'';
        $description = (isset($_POST['description']))?$_POST['description']:'';
        $starting_bid =( isset($_POST['starting_bid']))?$_POST['starting_bid']:'';
        $minimum_bid_increase = (isset($_POST['minimum_bid_increase']))?$_POST['minimum_bid_increase']:'';
        $ruleset = (isset($_POST['ruleset']))?$_POST['ruleset']:'';
        $type = (isset($_POST['type']))?$_POST['type']:'';
        $photos = [];//TODO


        $typeid = 0;
        foreach ($types as $type)
        {
            if ($type->type == $_POST["type"])
            {
                $typeid = $type->id;
            }
        }
        $rulesetid = 0;
        foreach ($rulesets as $ruleset)
        {
            if ($ruleset->ruleset == $_POST["ruleset"])
            {
                $rulesetid = $ruleset->id;
            }
        }

        $auction = Auction::create()
            ->setAuthor($author)
            ->setAuthorId($author_id)
            ->setDate($date)
            ->setName($name)
            ->setDescription($description)
            ->setStartingBid($starting_bid)
            ->setMinimumBidIncrease($minimum_bid_increase)
            ->setRuleset($ruleset->ruleset)
            ->setRulesetId($rulesetid)
            ->setType($type->type)
            ->setTypeId($typeid)
            ->setPhotos($photos)
            ->setAwaitingApproval(true);
        
        $this->auctionRepository->save($auction);

        $this->auctionViewRenderer->render($this->response,"send.php", );
        
        return $this->response;
    }

}