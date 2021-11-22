
<?php 
    $server = 'http://'.$_SERVER["SERVER_NAME"]; $port = ':'.$_SERVER["SERVER_PORT"];
    if (!isset($_SESSION)) session_start();
    $user_id = (isset($_SESSION["id"]))? '/'.$_SESSION["id"]: "";

?>

<nav id="main-navbar">
    <div id="main-nav-content">
        <ul class="main-nav-list">
            <li><a href="<?=$server.$port?>">Home</a></li>
            <li><a href="<?=$server.$port.'/auctions/user_auctions'?>">My auctions</a></li>
            <li><a href="<?=$server.$port.'/auctions/create'?>">Create auction</a></li>
            <?php 
            // user must login first to see his profile
            if ($user_id !== "") { ?>
            <li><a href="<?=$server.$port.'/users'.$user_id?>">My profile</a></li>
        <?php }
            else { ?>
            <li><a href="<?=$server.$port.'/login'?>">My profile</a></li>
        <?php }
            ?>
            
        </ul>
    </div>
</nav>