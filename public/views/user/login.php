<!-- GET /users/login -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<form action="/users/check" method="post">
    <label for="email"> E-mail:</label> <input type="text" name="email" ><br>
    <label for="password"> Password:</label> <input type="password" name="password"><br>
    <input type="submit">
</form>
<a href= "TODO"> Not registred yet ? </a>
<p <?php if(!isset($_GET['login'])){ echo "hidden";}?> style="color:red;"> Wrong name or username. </p>

<?php require_once "templates/footer.inc.php";?>