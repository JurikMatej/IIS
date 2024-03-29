<!-- GET /auctions/{id}/users -->
<!-- Singular auctions's users list -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="auction-users">
    <h2> Waiting users </h2>
    <div class="auction-pending-users-wrapper">
		<?php
        $waitingUsersSectionWritten = false;
		// Bids
		foreach ($waiting as $bid) {
            if ($bid->getUser() !== null) {
                if (!$waitingUsersSectionWritten)
                    $waitingUsersSectionWritten = true;

                echo "<div class='auction-pending-user-component' data-id='" . $bid->getUser()->getId() . "'>";
					echo "
                        <h3 style=\"color:purple;\">"
                        . $bid->getUser()->getFirstName() . " " . $bid->getUser()->getLastName() .
                        "</h3>"; ?>

                    <a href="/auctions/<?= $bid->getAuctionId() ?>/users/<?= $bid->getId() ?>/approve"
                       class="btn btn-success"
                       onclick="return confirm('Do you want to approve this user on this auction ?')">Approve</a>

                    <a href="/auctions/<?= $bid->getAuctionId() ?>/users/<?= $bid->getId() ?>/reject"
                       class="btn btn-danger"
                       onclick="return confirm('Do you want to reject this user from this auction ?')">Reject</a>
				<?= "<hr>" ?>
				<?= "</div>" ?>
            <?php }


		}
		?>
    </div>

    <?php
    if (!$waitingUsersSectionWritten)
        echo "<hr>";
    ?>

    <h2> Approved users </h2>
    <?php
        // Bids    
        foreach ($registred as $bid)
        {
            if ($bid->getUser() !== null)
            {
                echo "<h3 style=\"color:purple;\">" 
                . $bid->getUser()->getFirstName() . " " . $bid->getUser()->getLastName() .  "</h3>";?>

                <a href="/auctions/<?=$bid->getAuctionId()?>/users/<?=$bid->getId()?>/reject" class="btn btn-danger"
                onclick="return confirm('Do you want to remove this user from this auction ?')">Remove</a>

            <?php }
            
            echo "<hr>";
        }
    ?>

    
    <?php
        $datetime = $auction->getDate(); 
        $timelimit = $auction->getTimeLimit();
        $finished = false;
        $started = false;
        if ($datetime <= new DateTime())
        {
            $started  = true;
        }
        if ($timelimit != null)
        {
            $end = $datetime->add($timelimit);
            if ($end <= new DateTime())
            {
                $finished = true;
            }
        } ?>

        <div class="auction-winner-set-wrapper">
            <div class="auction-winner-set-component">
            <?php
            if ($auction->getWinnerId() == 0)
            {?>
                <h2> Define winner </h2>
                <?php if ($started)
                {
                    if ($timelimit == null || $finished )
                    {
                        if ($auction->getType() == "ascending-bid")
                        {?>
                            <p> Highest bid: </p>
                        <?php }
                        else
                        { ?>
                            <p> Lowest bid: </p>
                        <?php } ?>



                        <?php
                        if ($highest_lowest_bid != null && $highest_lowest_bid->getValue() != 0){?>
                            <h3>
                                <?=$highest_lowest_bid->getValue() ?> $ by <?=$highest_lowest_bid->getUser()->getFirstName()?>
                                <?=$highest_lowest_bid->getUser()->getLastName()?>
                            </h3>
                            <a href="/auctions/<?=$auction->getId()?>/winner/<?=$highest_lowest_bid->getUser()->getId()?>"
                            class="btn btn-success">
                                Set winner
                            </a>
                        <?php } ?>

                    <?php
                    }
                    else
                    {?>
                        <p>You have to wait until the auction ends, to define a winner!</p>
                    <?php }
                }
                else
                {?>
                    <p>You have to wait until the auction starts, to define a winner!</p>
                <?php }
            }
            else
            {?>
                <h2> Winner is <?=$auction->getWinner()->getFirstName()?> <?=$auction->getWinner()->getLastName()?>
                with bid <?=$highest_lowest_bid->getValue() ?> $</h2>
            <?php }?>
            </div>
        </div>
</div>
