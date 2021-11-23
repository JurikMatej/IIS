<!-- PUT/PATCH /auctions/{id}/register -->
<!-- button click on register in show.php goes here and registers user tro auction -->

<?php
    $dest = "/auctions/" . $auction_id ;
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