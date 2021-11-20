<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<div class="user-auctions-list">
    <?php 
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
<?php  } ?>
</div>


<?php require_once "templates/footer.inc.php";?>