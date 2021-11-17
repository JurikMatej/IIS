<!-- Home page for registered users-->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<div class="home-header">
<h1>Welcome to home page</h1>
</div>

<?php 
    session_start();
    if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) { ?>

<!-- Show login and reg button only for visitor/not logged user --> 
<nav id="log-reg-nav">
    <div id="log-reg-nav-content">
        <ul class="log-reg-nav-list">
            <li><a href="/login" class="btn btn-primary">Log in</a></li>
            <li><a href="/register" class="btn btn-primary">Register</a></li>
        </ul>
    </div>
</nav>

<?php 
    }  
    else { ?>

<!-- Show log out button only for logged user --> 
<nav id="logout-nav">
    <div id="logout-nav-content">
        <ul class="logout-nav-list">
            <li><a href="/logout" class="btn btn-primary" onclick="return confirm('Are you sure you want to log out ?')">Log out</a></li>
        </ul>
    </div>
</nav>

<?php 
    } ?>

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