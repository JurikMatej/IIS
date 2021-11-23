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
    if ($starting_failed)
    {
        header("Location: http://$name$port$dest" . "?failed=starting");
    }
    else if ($value_failed)
    {
        header("Location: http://$name$port$dest" . "?failed=value");
    }
    else if ($increase_failed)
    {
        header("Location: http://$name$port$dest" . "?failed=increase");
    }
    else
    {
        header("Location: http://$name$port$dest");
    }
    exit();
?>