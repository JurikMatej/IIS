<?php require_once "templates/user-operations.inc.php";?>

<div class="user-auctions-list">


<div class="user-auction">
    <h1 style="margin-bottom: 30px;">Your auctions</h1>
    <?php foreach ($user_auctions as $auction){
        require "templates/show_auction.inc.php";
    } ?>
</div>

<?php
$role = isset($_SESSION['role'])? $_SESSION['role']: '';
if ($role === "Admin" || $role === "Auctioneer") { ?>
    <div class="approver-auction">
        <h1 style="margin: 30px 0;">Auctions you coordinate</h1> 
        <?php foreach($approver_auctions as $auction) {
            require "templates/show_auction.inc.php";
        } ?>
    </div> 
<?php } ?>


<div class="user-bid-auction">
    <h1 style="margin-bottom: 30px;">Auctions where you bid</h1><?php
    foreach($auctions_where_user_bid as $auction) {
        require "templates/show_auction.inc.php";
    }?>
</div>

</div>