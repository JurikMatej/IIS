<!-- GET /auctions/{id} -->
<!-- Singular auctions's details -->

<div class="auction-detail">
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
                echo "</p> <p style=\"color:green;\">Running";
            }
            else
            {
                echo "</p> <p style=\"color:red;\">Finished";
                $finished = true;
            }
        }
        else 
        {
            // Calculating finish time
            $date = new DateTime();
            $date->setTime(0, 0);
            $diff = $auction->getTimeLimit()->diff($date,true);
            $a = $datetime->add($diff);
            if ($a > new DateTime())
            {
                echo "</p> <p style=\"color:green;\">Runing until: " . $a->format("d.m.Y H:i:s");
            }
            else
            {
                echo "</p> <p style=\"color:red;\">Finished on: " . $a->format("d.m.Y H:i:s");
                $finished = true;
            }
        }
    ?> </p>

    <p>Starting bid: <?=$auction->getStartingBid()?> $</p>

    <?php 
        if($auction->getTimeLimit() !== null && !$finished)
        {
            // Calculating time left
            $date = new DateTime();
            $a = $auction->getDate()->diff($date,true);
            echo " <p> Duration: " . $a->format("%d days and %H hours %i minutes left") . "</p>"; 
        }

        if($auction->getMinimumBidIncrease() !== 0 && !$finished) 
            echo " <p> Minimum bid increase: " . $auction->getMinimumBidIncrease() . " $ </p>"; 

        if($auction->getFormattedBiddingInterval() !== null && !$finished) 
            echo " <p> Bidding interval: " . $auction->getFormattedBiddingInterval() . "</p>"; 
    ?>

    <p>
        Author: <?php
            if ($auction->getAuthor() !== null) { ?>
                <a href="../../users/<?=$auction->getAuthor()->getId()?>"><?php echo $auction->getAuthor()->getFirstName() . " " . $auction->getAuthor()->getLastName()?></a> <?php }
            else
            {
                echo "Non existing author";
            }
        ?>
    </p>

    <p>
        Approver: <?php
            if ($auction->getApprover() !== null) { ?>
                <a href="../../users/<?=$auction->getApprover()->getId()?>"><?php echo $auction->getApprover()->getFirstName() . " " . $auction->getApprover()->getLastName()?></a> <?php }
            else
            {
                echo "Non existing approver";
            }
        ?>
    </p>

    <p>
        Winner: <?php
            if ($auction->getWinner() !== null) { ?>
                <a href="../../users/<?=$auction->getWinner()->getId()?>"><?php echo $auction->getWinner()->getFirstName() . " " . $auction->getWinner()->getLastName()?></a> <?php }
            else
            {
                echo "Auction has no winner yet";
            }
        ?>
    </p>

    <p>Type: <?php echo $auction->getType() . ", " . $auction->getRuleset()?></p>

    <?php
        for($i = 0; $i < count($auction->getPhotos()); $i++)
        {
            echo "<img src=\"" . $auction->getPhotos()[$i]->getPath() . 
            "\" alt=\"Photo of this auction\" width=\"500\" height=\"600\"><br>";
        }
    
        // allow editation of auction only for author and if auction has not started yet
        //session_start();
        $actual_date = new DateTime('now');
        $auction_date = $auction->getDate();
        if ($actual_date < $auction_date && $auction->getAuthorId() === $_SESSION['id']) { ?>
            <a href="<?=$auction->getId()?>/edit"  class="btn btn-primary">Edit</a>

    <?php }

        // button for registration on auction, not visible for author
        if ($auction->getAuthorId() !== $_SESSION['id']) { ?>
            <a href="<?=$auction->getId()?>/register" class="btn btn-primary" onclick="return confirm('Do you want to register on this auction ?')"> Register on auction </a>

    <?php } 
    
        // button for deletation of auction visible only for author and admin
        if ($auction->getAuthorId() === $_SESSION['id'] || $_SESSION['role'] === "Admin") { ?>
            <a href="<?=$auction->getId()?>/delete" class="btn btn-primary" onclick="return confirm('Do you want to delete on this auction ?')"> Delete</a>

    <?php  } ?>

    <br>
    <?php
        // Bids
        foreach ($bids as $bid)
        {
            if ($bid->getUser() !== null)
            {
                echo "<h3>" . $bid->getValue() ." by <a href=\"../../users/" . $bid->getUser()->getId() . "\" >" 
                . $bid->getUser()->getFirstName() . " " . $bid->getUser()->getLastName() .  "</a></h3>";
            }
            else
            {
                echo "<h3>" . $bid->getValue() ." by Non existing user</h3>";
            }
            
            echo "<hr>";
        }

    ?>
</div>