<!-- GET /users/create -->
<!-- Shows form to fill and send -> create an user -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<form action="/users/register" method="post">
    <label for="email"> E-mail:</label> <input type="text" name="email" ><br>
    <label for="password"> Password:</label> <input type="password" name="password"><br>
    <label for="first_name"> First name:</label> <input type="text" name="first_name"><br>
    <label for="last_name"> Last name:</label> <input type="text" name="last_name"><br>
    <label for="address"> Address:</label> <input type="text" name="address"><br>
    <input type="submit" value="Register">
</form>
<p <?php if(!isset($_GET['non_empty_register'])){ echo "hidden";}?> style="color:red;"> All labels must be filled ! </p>
<p <?php if(!isset($_GET['mail_register'])){ echo "hidden";}?> style="color:red;"> Email address already exists ! </p>

<?php require_once "templates/footer.inc.php";?>