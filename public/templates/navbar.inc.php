
<?php $server = 'http://'.$_SERVER["SERVER_NAME"]; $port = ':'.$_SERVER["SERVER_PORT"];?>

<nav id="main-navbar">
    <div id="main-nav-content">
        <ul class="main-nav-list">
            <li><a href="<?=$server.$port?>">Home</a></li>
            <li><a href="#">My auctions</a></li>
            <li><a href="<?=$server.$port.'/auctions/create'?>">Create auction</a></li>
            <li><a href="#">My profile</a></li>
        </ul>
    </div>
</nav>