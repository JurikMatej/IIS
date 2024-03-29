<?php


namespace App\Application\Actions\Auction;

use App\Domain\Auction\Auction;
use App\Domain\AuctionPhoto\AuctionPhoto;
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
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$dest");
            exit();
        }

        $this->logger->info("Auctions was created.");
        $rulesets = $this->auctionRepository->getAuctionRulesets();
        $types = $this->auctionRepository->getAuctionTypes();

        $author_id = (int) (isset($_SESSION['id']))?$_SESSION['id']:'';
        $author = $this->userRepository->findUserOfId(intval($author_id));

        $hours = (int)(isset($_POST['hours']))?$_POST['hours']:'0';
        $minutes = (int)(isset($_POST['minutes']))?$_POST['minutes']:'0';

        if ($hours  ===  '0' && $minutes === '0')
        {
            $time_limit = null;
        }
        else
        {
            $time_limit = new DateTime();
            $zero = clone $time_limit;
            $time_limit->setTime($hours, $minutes);
            $zero->setTime(0,0);
            $time_limit = $time_limit->diff($zero);
        }
       
        $name = (isset($_POST['name']))?$_POST['name']:'';
        $description = (isset($_POST['description']))?$_POST['description']:'';
        $starting_bid =( isset($_POST['starting_bid']))?$_POST['starting_bid']:'';
        $minimum_bid_increase = (int)(isset($_POST['minimum_bid_increase']))?$_POST['minimum_bid_increase']:'';
        $rulesetId = (int)((isset($_POST['ruleset']))?$_POST['ruleset']:'');
        $typeId = (int)((isset($_POST['type']))?$_POST['type']:'');

        if ($typeId === 0 && $rulesetId === 2) // closed
        {
            $typeId = 1; // only ascending bid
        }


        $typeString = '';
        foreach ($types as $typ)
        {
            if ($typ->id == $typeId)
            {
                $typeString = $typ->type;
            }
        }

        $rulesetString = '';
        foreach ($rulesets as $rule)
        {
            if ($rule->id == $rulesetId)
            {
                $rulesetString = $rule->ruleset;
            }
        }

        $auction = Auction::create()
            ->setAuthor($author)
            ->setAuthorId($author_id)
            ->setTimeLimit($time_limit)
            ->setName($name)
            ->setDescription($description)
            ->setStartingBid($starting_bid)
            ->setMinimumBidIncrease(intval($minimum_bid_increase))
            ->setRuleset($rulesetString)
            ->setRulesetId($rulesetId)
            ->setType($typeString)
            ->setTypeId($typeId)
            ->setAwaitingApproval(true);

        // photo storing
        $photos = ((isset($_FILES['imgs']) && isset($_POST["submit"]))?$_FILES['imgs']:[]);
        $total = count($photos['name']);
        $auction_photos  = [];
    
        for( $i = 0; $i < $total; $i++ ) 
        {
            //Get the temp file path
            $tmpFilePath = $photos['tmp_name'][$i];

            $destination_path = getcwd().DIRECTORY_SEPARATOR;
            $target_dir = "assets/images/";
            $target_file = $destination_path . $target_dir . basename($photos["name"][$i]);
            move_uploaded_file($tmpFilePath, $target_file);

            $auction_photos[$i] = AuctionPhoto::create()
                ->setPath(basename($photos["name"][$i]))
                ->setAuctionId($auction->getId());
        }
        if($auction_photos != [])
        {
            $auction->setPhotos($auction_photos);
        }

        $this->auctionRepository->save($auction);

        $this->auctionViewRenderer->render($this->response,"send.php");
        
        return $this->response;
    }

}