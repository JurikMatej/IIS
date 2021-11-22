<!-- GET /users/create -->
<!-- Shows form to fill and send -> create an user -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<div class="reg-form">
    <h2>Fill form to register in auction system</h2>
    <form action="/users/register" method="post" >
        <label for="email"> E-mail:</label> <input type="text" name="email" id="email" > <br>
        <label for="password"> Password:</label> <input type="password" name="password" id="password">
        <i class="bi bi-eye-slash" id="togglePassword"></i><br>
        <label for="first_name"> First name:</label> <input type="text" name="first_name" id="name"> <br>
        <label for="last_name"> Last name:</label> <input type="text" name="last_name" id="surname"> <br>
        <label for="address"> Address:</label> <input type="text" name="address" id="address"> <br>
        <input type="submit" value="Register" id="reg-button">
    </form>
    <p <?php if(!isset($_GET['mail_register'])){ echo "hidden";}?> style="color:red;"> Email address already exists ! </p>

    <?php if (isset($_GET['mail_register'])) { 
            if (!isset($_SESSION)) session_start(); ?> 
            <!-- Fill input fields once again with content before error   -->
            <script > 
            document.getElementById("email").value = "<?php echo $_SESSION["email"]?>";
            document.getElementById("password").value = "<?php echo $_SESSION["password"]?>";
            document.getElementById("name").value = "<?php echo $_SESSION["first_name"]?>";
            document.getElementById("surname").value = "<?php echo $_SESSION["last_name"]?>";
            document.getElementById("address").value = "<?php echo $_SESSION["address"]?>";
            </script>

    <?php 
            unset($_SESSION["email"]);
            unset($_SESSION["password"]);
            unset($_SESSION["first_name"]);
            unset($_SESSION["last_name"]);
            unset($_SESSION["address"]);
            } ?>
</div>

<script>
    document.getElementById("email").required = true;
    document.getElementById("name").required = true;
    document.getElementById("password").required = true;
    document.getElementById("surname").required = true;

    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye / eye slash icon
        this.classList.toggle('bi-eye');
    });

</script>

<?php require_once "templates/footer.inc.php";?>