<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>
<?php require_once "templates/user-operations.inc.php";?>

<div class="user-auctions-list">
    <?php 
         ?><h1 style="margin-bottom: 30px;">Your auctions</h1><?php
        foreach($user_auctions as $auction) { ?>
            <div class="user-auction">
            <h3><a href="/auctions/<?php echo $auction->getId()?>"> <?php echo $auction->getName();?> </a></h3>
            <h3>Type of auction: <?php echo $auction->getType();?></h3>
            <h3>Rule of auction: <?php echo $auction->getRuleset();?></h3>
            <?php
                if ($auction->getPhotos() !== [])
                    echo "<img src=\"" . $auction->getPhotos()[0]->getPath()
                    . "\" alt=\"First photo of this auction\" width=\"500\" height=\"600\">";
            ?>
        </div>
<?php  } 

        $role = isset($_SESSION['role'])? $_SESSION['role']: '';
        if ($role === "Admin" || $role === "Auctioneer") {
?>
            
        <h1 style="margin: 30px 0;">Auctions you coordinate</h1> <?php
          foreach($approver_auctions as $auction) { ?>
            <div class="approver-auction">
            <h3><a href="/auctions/<?php echo $auction->getId()?>"> <?php echo $auction->getName();?> </a></h3>
            <h3>Type of auction: <?php echo $auction->getType();?></h3>
            <h3>Rule of auction: <?php echo $auction->getRuleset();?></h3>
            <?php
                if ($auction->getPhotos() !== [])
                    echo "<img src=\"" . $auction->getPhotos()[0]->getPath()
                    . "\" alt=\"First photo of this auction\" width=\"500\" height=\"600\">";
            ?>
            </div>
    
        <?php } 
        
        } ?>
</div>


<?php require_once "templates/footer.inc.php";?>