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
</div>