<!-- GET /users/create -->
<!-- Shows form to fill and send -> create an user -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<div class="reg-form">
    <h2>Fill form to register in auction system</h2>
    <form action="/users/register" method="post" >
        <label for="email"> E-mail:</label> <input type="text" name="email" id="email" > <br>
        <label for="password"> Password:</label> <input type="password" name="password" id="password"> <br>
        <label for="first_name"> First name:</label> <input type="text" name="first_name" id="first_name"> <br>
        <label for="last_name"> Last name:</label> <input type="text" name="last_name" id="last_name"> <br>
        <label for="address"> Address:</label> <input type="text" name="address" id="address"> <br>
        <input type="submit" value="Register" id="reg-button">
    </form>
    <p <?php if(!isset($_GET['non_empty_register'])){ echo "hidden";}?> style="color:red;"> All labels must be filled ! </p>
    <p <?php if(!isset($_GET['mail_register'])){ echo "hidden";}?> style="color:red;"> Email address already exists ! </p>

    <?php if (isset($_GET['non_empty_register']) || isset($_GET['mail_register'])) { 
            session_start(); ?> 
            <!-- Fill input fields once again with content before error   -->
            <script > 
            document.getElementById("email").value = "<?php echo $_SESSION["email"]?>";
            document.getElementById("password").value = "<?php echo $_SESSION["password"]?>";
            document.getElementById("first_name").value = "<?php echo $_SESSION["first_name"]?>";
            document.getElementById("last_name").value = "<?php echo $_SESSION["last_name"]?>";
            document.getElementById("address").value = "<?php echo $_SESSION["address"]?>";
            </script>

    <?php } ?>
</div>

<?php require_once "templates/footer.inc.php";?>