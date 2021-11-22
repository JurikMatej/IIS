<!-- GET /auctions -->
<!-- All auctions's  -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="wait-auctions-list">
    <h1> Waiting for approval </h1>

    <?php foreach ($auctions as $auction){ ?>

    <?php
        if ($auction->getPhotos() !== [])
            echo "<img src=\"" . $auction->getPhotos()[0]->getPath()
            . "\" alt=\"First photo of this auction\" width=\"500\" height=\"600\">";
    ?>
    <h3><a href="/auctions/waiting/<?php echo $auction->getId()?>"> <?php echo $auction->getName();?> </a></h3>
    <h2> <?php 
        $datetime = $auction->getDate(); 
        echo "Created: " . $datetime->format("d.m.Y H:i:s");
    ?> 
    </h2>
    <p>Type : <?php echo $auction->getType(); echo " , ".$auction->getRuleset();?></p>
    <p>Author : <?php 
        if ($auction->getAuthor() !== null)
            echo $auction->getAuthor()->getFirstName() . " " . $auction->getAuthor()->getLastName();
        else 
            echo "Non existing user";
    ?></p>
    <hr>

    <?php } ?>
</div>