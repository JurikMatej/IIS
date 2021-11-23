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
            <p>Description: <?php echo $auction->getDescription();?></p>
            <h3>Type of auction: <?php echo $auction->getType();?></h3>
            <h3>Rule of auction: <?php echo $auction->getRuleset();?></h3>
            <p><?php 
                $datetime = $auction->getDate(); 
                $timelimit = $auction->getTimeLimit();
                $finished = false;
                $started = false;
                if ($datetime > new DateTime())
                {
                    echo "Starting on: " . $datetime->format("d.m.Y H:i:s");
                }
                else
                {
                    if ($auction->getApprover() !== null) {
                        echo "Started on: " . $datetime->format("d.m.Y H:i:s");
                        $started  = true;
                    }
                    else {
                        echo "Created on: " . $datetime->format("d.m.Y H:i:s");
                    }
                }
                if ($timelimit == null)
                {
                    if (!$started)
                    {
                        echo "</p> <p style=\"color:blue;\">Not started yet";
                    }
                    else if ($auction->getWinnerId() == null)
                    {
                        echo "</p> <p style=\"color:green;\">Running";
                    }
                    else
                    {
                        echo "</p> <p style=\"color:red;\">Finished";
                        $finished = true;
                    }
                }
                // print timelimit only for approved auctions
                else if ($auction->getApprover() !== null)
                {
                    $end = $datetime->add($timelimit);
                    if ($end > new DateTime() && $started == true)
                    {
                        echo "</p> <p style=\"color:green;\">Runing until: " . $end->format("d.m.Y H:i:s");
                    }
                    else if ($end > new DateTime() && $started == false)
                    {
                        echo "</p> <p style=\"color:blue;\">Runing until: " . $end->format("d.m.Y H:i:s");
                    }
                    else
                    {
                        echo "</p> <p style=\"color:red;\">Finished on: " . $end->format("d.m.Y H:i:s");
                        $finished = true;
                    }
                }
            ?> </p>
            <?php
                if ($auction->getPhotos() !== [])
                    echo "<img src=\"" . $auction->getPhotos()[0]->getPath()
                    . "\" alt=\"First photo of this auction\" width=\"500\" height=\"600\">";
            ?>
        </div>
    <?php } ?>
    
</div>

<?php require_once "templates/footer.inc.php";?>