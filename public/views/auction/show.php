<!-- GET /auctions/{id} -->
<!-- Singular auctions's details -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="auction-detail">
    <h1> <?=$auction->getName()?></h1>
    <p>Description: <?=$auction->getDescription()?></p>
    <p><?php 
        $datetime = $auction->getDate(); 
        $timelimit = $auction->getTimeLimit();
        $finished = false;
        $started = false;
        if ($datetime > new DateTime())
        {
            echo "Starting on: " . $datetime->format("d.m.Y H:i:s");
        }
        else
        {
            if ($auction->getApprover() !== null) {
                echo "Started on: " . $datetime->format("d.m.Y H:i:s");
                $started  = true;
            }
            else {
                echo "Created on: " . $datetime->format("d.m.Y H:i:s");
            }
        }
        if ($timelimit == null)
        {
            if (!$started)
            {
                echo "</p> <p style=\"color:blue;\">Not started yet";
            }
            else if ($auction->getWinnerId() == null)
            {
                echo "</p> <p style=\"color:green;\">Running";
            }
            else
            {
                echo "</p> <p style=\"color:red;\">Finished";
                $finished = true;
            }
        }
        // print timelimit only for approved auctions
        else if ($auction->getApprover() !== null)
        {
            $end = $datetime->add($timelimit);
            if ($end > new DateTime())
            {
                echo "</p> <p style=\"color:green;\">Runing until: " . $end->format("d.m.Y H:i:s");
            }
            else
            {
                echo "</p> <p style=\"color:red;\">Finished on: " . $end->format("d.m.Y H:i:s");
                $finished = true;
            }
        }
    ?> </p>

    <p>Starting bid: <?=$auction->getStartingBid()?> $</p>

    <?php 
        if($auction->getTimeLimit() !== null && !$finished && $auction->getApprover() !== null)
        {
            // Calculating time left
            $date = new DateTime();
            $time_left = $end->diff($date,true);
            echo " <p> Duration: " . $time_left->format("%d days and %H hours %i minutes left") . "</p>"; 
        }

        if($auction->getMinimumBidIncrease() !== 0 && !$finished) 
            echo " <p> Minimum bid increase: " . $auction->getMinimumBidIncrease() . " $ </p>"; 

        // if($auction->getFormattedBiddingInterval() !== null && !$finished) 
        //     echo " <p> Bidding interval: " . $auction->getBiddingInterval()->format("%I minutes") . "</p>"; 
    ?>

    <p>
        Author: <?php
            if ($auction->getAuthor() !== null) { ?>
                <?php echo $auction->getAuthor()->getFirstName() . " " . $auction->getAuthor()->getLastName()?> <?php }
            else
            {
                echo "Non existing author";
            }
        ?>
    </p>

    <p>
        Approver: <?php
            if ($auction->getApprover() !== null) { ?>
                <?php echo $auction->getApprover()->getFirstName() . " " . $auction->getApprover()->getLastName()?><?php }
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
        if ($actual_date < $auction_date && $auction->getAuthorId() === $_SESSION['id'] && !$started) { ?>
            <a href="<?=$auction->getId()?>/edit"  class="btn btn-primary">Edit</a>

    <?php }
    
        // button for deletation of auction visible only for author and admin
        if (($auction->getAuthorId() === $_SESSION['id'] || $_SESSION['role'] === "Admin" )&& !$started) { ?>
            <a href="<?=$auction->getId()?>/delete" class="btn btn-primary" 
            onclick="return confirm('Do you want to delete this auction ?')"> Delete</a>

    <?php  } ?>

    <br>

    <?php 
        // button for registration on auction, not visible for author
        if ($auction->getAuthorId() !== $_SESSION['id'] &&  $auction->getApproverId() !== $_SESSION['id'] && !$finished) { ?>
            <a href="<?=$auction->getId()?>/register" class="btn btn-primary" id="RegisterButton"
            onclick="return confirm('Do you want to register for this auction ?')"
            > Register on auction </a>
    <?php } ?>
    <?php 
        // enable to admin and licitator to see list of all users registred for this auction
        //session_start();
        $role = isset($_SESSION['role'])? $_SESSION['role'] : '';
        if (($role === "Auctioneer" && $auction->getApproverId() === $_SESSION['id']) || $role === "Admin") {?>
            <a href="/auctions/<?=$auction->getId()?>/users" class="btn btn-primary">See registered users</a>
    <?php } ?>
    <?php 
    // bidding available after registration
    if ($started && !$finished){
        if ($is_approved) { ?>
            <form action="/auctions/<?=$auction->getId()?>/bid" method="post">
                <label for="value"> Value: </label>
                <input type="number" name="value" id="value" min="0" >
                <label for="value"> $</label><br>
                <input type="submit" value="Place bid" id="submitBid" class="btn btn-primary">
            </form>
            <?php if (isset($_GET['failed'])) :?>
                <?php if ($auction->getType() === "ascending-bid") :?>
                    <?php if ($_GET['failed'] === 'starting') :?>
                        <p style="color:red;"> Value has to be higger than Starting bid </p>
                    <?php elseif ($_GET['failed'] === 'value') :?>
                        <p style="color:red;"> Value has to be higger than the highest bid</p>
                    <?php elseif ($_GET['failed'] === 'increase') :?>
                        <p style="color:red;"> Value has to be higger than the highest bid by Minimum bid increase</p>
                    <?php endif;?>
                <?php else :?>
                    <?php if ($_GET['failed'] === 'starting') :?>
                        <p style="color:red;"> Value has to be lower than Starting bid </p>
                    <?php elseif ($_GET['failed'] === 'value') :?>
                        <p style="color:red;"> Value has to be lower than the lowest bid</p>
                    <?php endif;?>
                <?php endif;?>
            <?php endif;
        }
    } ?>

<?php if ($is_registred) :?>
    <?php if ($is_approved) :?>
        <script>
            document.getElementById("RegisterButton").hidden = true;
        </script>
    <?php else : ?>
        <script>
            elem = document.getElementById("RegisterButton");
            elem.classList.add("disable");
            document.getElementById("RegisterButton").innerText = "Waiting for approval";
        </script>
    <?php endif; ?>
<?php endif; ?>
<hr>
<?php
    // Bids
    if ($started)
    {
        if($auction->getType() === "descending-bid")
        {
            $bids = array_reverse($bids);
        }
        if ($auction->getRuleset() === "open")
        {
            foreach ($bids as $bid)
            {
                if ($bid->getAwaitingApproval() === false && $bid->getValue() !== 0)
                {
                    if ($bid->getUser() !== null)
                    {
                        echo "<h3>" . $bid->getValue() ." $ by <a href=\"../../users/" . $bid->getUser()->getId() . "\" >" 
                        . $bid->getUser()->getFirstName() . " " . $bid->getUser()->getLastName() .  "</a></h3>";
                    }
                    else
                    {
                        echo "<h3>" . $bid->getValue() ." $ by Non existing user</h3>";
                    }
                
                    echo "<hr>";
                }
            }
        }
        else //closed
        {
            foreach ($bids as $bid)
            {
                if ($bid->getAwaitingApproval() === false && $bid->getValue() !== 0)
                {
                    if ($bid->getUser() !== null && $bid->getUser()->getId() === $_SESSION['id'])
                    {?>
                        <h3 id="closedBid"><?=$bid->getValue()?> $ by you</h3>
                    <?php }
                }
            }
        }
    }
?>

<script>
    var closedBid = document.getElementById("closedBid");
    var submitBid = document.getElementById("submitBid");
    if (closedBid) 
    {
        submitBid.disabled = true;
        submitBid.value = "You can only place one bid on closed auction";
    }
</script>
</div>