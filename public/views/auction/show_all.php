<!-- GET /auctions -->
<!-- All auctions's  -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="waiting-auction-button">
    <a href="/auctions/waiting" class="btn btn-primary">Auctions waiting for approval </a>
</div>

<?php foreach ($auctions as $auction){ ?>
    
<div class="auction-list">
    <?php
        if ($auction->getPhotos() !== [])
            echo "<img src=\"" . $auction->getPhotos()[0]->getPath()
            . "\" alt=\"First photo of this auction\" width=\"500\" height=\"600\">";
    ?>
    <h3><a href="/auctions/<?php echo $auction->getId()?>"> <?php echo $auction->getName();?> </a></h3>
    <h2> <?php 
        $datetime = $auction->getDate(); 
        $timelimit = $auction->getTimeLimit();
        $started = false;
        if ($datetime > new DateTime())
        {
            echo "Starting on: " . $datetime->format("d.m.Y H:i:s");
        }
        else
        {
            echo "Started on: " . $datetime->format("d.m.Y H:i:s");
            $started = true;
        }
        
        if ($timelimit == null)
        {
            if (!$started)
            {
                echo "</h2> <h2 style=\"color:blue;\">Not started yet";
            }
            else if ($auction->getWinnerId() == null)
            {
                echo "</h2> <h2 style=\"color:green;\">Running";
            }
            else
            {
                echo "</h2> <h2 style=\"color:red;\">Finished";
            }
        }
        else 
        {
            // Calculating finish time
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
                echo "</h2> <h2 style=\"color:red;\">Finished on: " . $end->format("d.m.Y H:i:s");
            }
        }
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
