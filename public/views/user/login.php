<!-- GET /users/login -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<div class="log-form">
    <h2>Fill form to log in auction system</h2>
    <form action="/users/check" method="post">
        <label for="email"> E-mail:</label> <input type="text" name="email" id="email"><br>
        <label for="password"> Password:</label> <input type="password" name="password" id="password"><br>
        <input type="submit" value="Log in" id="log-button">
    </form>
    <a href= "\register"> Not registred yet ? </a>
    <p <?php if(!isset($_GET['login'])){ echo "hidden";}?> style="color:red;"> Wrong name or username. </p>

    <?php if (isset($_GET['login'])) { 
            session_start(); ?> 
            <!-- Fill input fields once again with content before error   -->
            <script > 
            document.getElementById("email").value = "<?php echo $_SESSION["email"]?>";
            document.getElementById("password").value = "<?php echo $_SESSION["password"]?>";
            </script>

    <?php } ?>
</div>

<?php require_once "templates/footer.inc.php";?>