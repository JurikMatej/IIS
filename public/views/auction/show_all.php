<!-- GET /auctions -->
<!-- All auctions's  -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="waiting-auction-button">
    <a href="/auctions/waiting" class="btn btn-primary">Auctions waiting for approval </a>
</div>

<div class="auction-list">
    <?php foreach ($auctions as $auction){
        require "templates/show_auction.inc.php";
    } ?>
</div>
