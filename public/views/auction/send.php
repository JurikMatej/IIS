<!-- PUT/PATCH /auctions/create -->
<!-- create.php form goes to this URL with PUT/PATCH METHOD ("AUCTION HAS BEEN SEND" View) -->

<?php
    // TODO redirect to list of user's auctions, so far redirect to home
    $dest = "/auctions" ;
    $script = $_SERVER["PHP_SELF"];
    if (strpos($dest, '/') === 0) { // absolute path
        $path = $dest;
    } else {
        $path = substr($script, 0,
        strrPos($script, "/"))."/$dest";
    }

    
    $name = $_SERVER["SERVER_NAME"];
    $port = ':'.$_SERVER["SERVER_PORT"];
    header("Location: http://$name$port");
    exit();
?>