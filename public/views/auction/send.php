<!-- PUT/PATCH /auctions/create -->
<!-- create.php form goes to this URL with PUT/PATCH METHOD ("AUCTION HAS BEEN SEND" View) -->

<?php
    $dest = "/auctions/user_auctions" ;
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