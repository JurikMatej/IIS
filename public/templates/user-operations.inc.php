
<!-- login/out and registration buttons visible in all views -->
<?php 
    if (!isset($_SESSION))  session_start();
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

<?php 
    // enable to admin and licitator to see list of all auctions
        $role = isset($_SESSION['role'])? $_SESSION['role'] : '';
        if ($role === "Admin" || $role === "Auctioneer") {
            ?>
            <li><a href="/auctions" class="btn btn-primary">See all auctions</a></li> 
    <?php } 
        
        if ($role === "Admin") { ?>
            <li><a href="/users" class="btn btn-primary">See all users</a></li> 
    <?php } 

    } ?>
        </ul>
    </div>
</nav>