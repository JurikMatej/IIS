<!-- GET /auctions/{id}/users -->
<!-- Singular auctions's users list -->

<div class="auction-users">
    <h2> Waiting users </h2>
    <?php
        // Bids    
        foreach ($waiting as $bid)
        {
            if ($bid->getUser() !== null)
            {
                echo "<h3 style=\"color:purple;\">" 
                . $bid->getUser()->getFirstName() . " " . $bid->getUser()->getLastName() .  "</h3>";?>

                <a href="/auctions/<?=$bid->getAuctionId()?>/users/<?=$bid->getId()?>/approve" class="btn btn-primary"
                onclick="return confirm('Do you want to approve this user on this auction ?')">Approve</a>

                <a href="/auctions/<?=$bid->getAuctionId()?>/users/<?=$bid->getId()?>/reject" class="btn btn-primary"
                onclick="return confirm('Do you want to reject this user from this auction ?')">Reject</a>

            <?php }
            
            echo "<hr>";
        }
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

                <a href="/auctions/<?=$bid->getAuctionId()?>/users/<?=$bid->getId()?>/reject" class="btn btn-primary"
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
        }

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
                    <?php }
                        
                    if ($highest_lowest_bid != null && $highest_lowest_bid->getValue() != 0){?>
                        <h3> <?=$highest_lowest_bid->getValue() ?> $ by <?=$highest_lowest_bid->getUser()->getFirstName()?> 
                        <?=$highest_lowest_bid->getUser()->getLastName()?></h3>
                        <a href="/auctions/<?=$auction->getId()?>/winner/<?=$highest_lowest_bid->getUser()->getId()?>" 
                        class="btn btn-primary">Set winner</a>
                    <?php }
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