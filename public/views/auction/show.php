<!-- GET /auctions/{id} -->
<!-- Singular auctions's details -->

<h1> <?=$auction->getName()?></h1>
<p>Description: <?=$auction->getDescription()?></p>
<p><?php 
    $datetime = $auction->getDate(); 
    $timelimit = $auction->getTimeLimit();
    $finished = false;
    echo "Started on: " . $datetime->format("d.m.Y H:i:s");
    if ($timelimit == null)
    {
        if ($auction->getWinnerId() == null)
        {
            echo "</p> <p>Running";
        }
        else
        {
            echo "</p> <p>Finished";
            $finished = true;
        }
    }
    else 
    {
        $date = new DateTime();
        $date->setTime(0, 0);
        $diff = $auction->getTimeLimit()->diff($date,true);
        $a = $datetime->add($diff);
        if ($a > new DateTime())
        {
            echo "</p> <p>Runing until: " . $a->format("d.m.Y H:i:s");
        }
        else
        {
            echo "</p> <p>Finished on: " . $a->format("d.m.Y H:i:s");
            $finished = true;
        }
    }
?> </p>

<p>Starting bid: <?=$auction->getStartingBid()?> $</p>

<?php 
    if($auction->getTimeLimit() !== null && !$finished) 
        echo " <p> Duration: " . $diff->format("%H:%i") . "</p>"; 

    if($auction->getMinimumBidIncrease() !== 0 && !$finished) 
        echo " <p> Minimum bid increase: " . $auction->getMinimumBidIncrease() . " $ </p>"; 

    if($auction->getFormattedBiddingInterval() !== null && !$finished) 
        echo " <p> Bidding interval: " . $auction->getFormattedBiddingInterval() . "</p>"; 
?>

<p>
    Author: <a href="../../users/<?=$auction->getAuthor()->getId()?>"><?=$auction->getAuthor()->getFirstName() .
    " " . $auction->getAuthor()->getLastName()?> </a>
</p>

<?php
    if ($auction->getApprover() !== null)
    {
        echo "<p> Approver: <a href=\"../../users/" . $auction->getApprover()->getId() . "\" >" 
        . $auction->getApprover()->getFirstName() . " " . $auction->getApprover()->getLastName() . "</a> </p>";
    }
    if ($auction->getWinner() !== null)
    {
        echo "<p> Winner: <a href=\"../../users/" . $auction->getWinner()->getId() . "\" >" 
        . $auction->getWinner()->getFirstName() . " " . $auction->getWinner()->getLastName() .  "</a> </p>";
    }
?>

<p>Type: <?php echo $auction->getType() . ", " . $auction->getRuleset()?></p>

<?php
    for($i = 0; $i < count($auction->getPhotos()); $i++)
    {
        echo "<img src=\"" . $auction->getPhotos()[$i]->getPath() . 
        "\" alt=\"Photo of this auction\" width=\"500\" height=\"600\">";
    }
?>
<br>
<a href="<?=$auction->getId()?>/edit">Edit</a>
