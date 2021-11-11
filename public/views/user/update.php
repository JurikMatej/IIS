<!-- PUT/PATCH /users/{user} -->
<!-- edit.php form goes to this URL with PUT/PATCH METHOD ("USER HAS BEEN EDITTED" View) -->

<?php
    $dest = "/users/" . $user->getId();
    $script = $_SERVER["PHP_SELF"];
    if (strpos($dest, '/') === 0) { // absolute path
        $path = $dest;
    } else {
        $path = substr($script, 0,
        strrPos($script, "/"))."/$dest";
    }
    $name = $_SERVER["SERVER_NAME"];
    $port = ':'.$_SERVER["SERVER_PORT"];
    header("Location: http://$name$port$path");
    exit();
?>