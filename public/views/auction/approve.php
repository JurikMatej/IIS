<!-- GET /auctions/create -->
<!-- Shows form to fill and send , creates an auction -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>
<?php require_once "templates/user-operations.inc.php";?>

<div class="auction-approve">
    <?php
        $_SESSION['auction_id'] = $auction->getId();
        $can_approve = true;
        if (isset($_SESSION['id']))
            if ($auction->getAuthor() !== null)
                if($_SESSION['id'] === $auction->getAuthor()->getId())
                    $can_approve = false;
            else
				$can_approve = false;
        
    ?>

    <h2><?php echo $auction->getName()?></h2>
    <h3>
        Author: <?php
            if ($auction->getAuthor() !== null)
            {
                echo $auction->getAuthor()->getFirstName() .
                " " . $auction->getAuthor()->getLastName();
            }
            else
            {
                echo "Non existing user";
            }
        ?>
    </h3>
    <p>Description: <?=$auction->getDescription()?></p>
    <p>Starting bid: <?=$auction->getStartingBid()?> $</p>
    <?php 
        if($auction->getTimeLimit() !== null)
        {
            echo " <p> Time limit: " . $auction->getTimeLimit()->format('%H hours %I minutes') . "</p>"; 
        }
        if($auction->getMinimumBidIncrease() !== 0) 
            echo " <p> Minimum bid increase: " . $auction->getMinimumBidIncrease() . " $ </p>"; 

        if($auction->getFormattedBiddingInterval() !== null) 
            echo " <p> Bidding interval: " . $auction->getBiddingInterval()->format("%I minutes") . "</p>"; 
    ?>

    <form action="/auctions/update" method="post">
        <label for="date"> Date:</label>
        <input type="datetime-local" name="date" id="date"><br>
        <br>
        <input type="submit" value="Approve" <?php if (!$can_approve) echo "disabled";?>>
    </form>
    <?php if (!$can_approve) echo "<p style=\"color:red;\"> Approver can not be author</p>";?>

    <script>
        document.getElementById("date").required = true;

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var ii = today.getMinutes();
        if(dd<10){
            dd = '0' + dd
        }
        if(mm<10){
            mm = '0' + mm
        }
        if(hh<10){
            hh = '0' + hh
        }
        if(ii<10){
            ii = '0' + ii
        }

        today = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + ii;
        document.getElementById("date").setAttribute("min", today);
        document.getElementById("date").setAttribute("value", today);
    </script>
</div>

<?php require_once "templates/footer.inc.php";?>