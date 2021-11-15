<!-- Home page for registered users-->

<?php
session_start();
if (!isset($_SESSION['id']))
{
    $dest = "/error" ;
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
}
?>

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<h1>Welcome to home page</h1>

<?php require_once "templates/footer.inc.php";?>