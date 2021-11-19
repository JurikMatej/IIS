<!-- GET /auctions -->
<!-- All auctions's  -->

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
        echo "Started on: " . $datetime->format("d.m.Y H:i:s");
        if ($timelimit == null)
        {
            if ($auction->getWinnerId() == null)
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
            if ($end > new DateTime())
            {
                echo "</h2> <h2 style=\"color:green;\">Runing until: " . $end->format("d.m.Y H:i:s");
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
