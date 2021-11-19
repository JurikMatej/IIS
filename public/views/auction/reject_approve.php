<!-- /auctions/{id}/users/reject or /auctions/{id}/users/approve-->
<!-- users.php goes to this URL when user approved/rejected/removed -->

<?php
    $dest = "/auctions/" . $auctionId ."/users" ;
    $script = $_SERVER["PHP_SELF"];
    if (strpos($dest, '/') === 0) { // absolute path
        $path = $dest;
    } else {
        $path = substr($script, 0,
        strrPos($script, "/"))."/$dest";
    }

    
    $name = $_SERVER["SERVER_NAME"];
    $port = ':'.$_SERVER["SERVER_PORT"];
    header("Location: http://$name$port$dest");
    exit();
?>