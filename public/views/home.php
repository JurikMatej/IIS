<!-- Home page for registered users-->


<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>
<?php require_once "templates/user-operations.inc.php";?>

<div class="home-header">
<h1>Welcome to home page</h1>
</div>
        

<div class="scroll-container">
    <style>
    /* Set width of scrollbar*/
    ::-webkit-scrollbar {width: 15px;}

    /* Track */
    ::-webkit-scrollbar-track {
    background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
    background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
    background: #555;
    }

    </style>

    <?php foreach($auctions as $auction) { ?>
        <div class="home-auction">
            <h3><a href="/auctions/<?php echo $auction->getId()?>"> <?php echo $auction->getName();?> </a></h3>
            <h3>Type of auction: <?php echo $auction->getType();?></h3>
            <h3>Rule of auction: <?php echo $auction->getRuleset();?></h3>
            <?php
                if ($auction->getPhotos() !== [])
                    echo "<img src=\"" . $auction->getPhotos()[0]->getPath()
                    . "\" alt=\"First photo of this auction\" width=\"500\" height=\"600\">";
            ?>
        </div>
    <?php } ?>
    
</div>

<?php require_once "templates/footer.inc.php";?>