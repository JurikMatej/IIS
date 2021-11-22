<!-- GET /users/login -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<div class="log-form">
    <h2>Fill form to log in auction system</h2>
    <form action="/users/check" method="post">
        <label for="email"> E-mail:</label> <input type="text" name="email" id="email"><br>
        <label for="password"> Password:</label> <input type="password" name="password" id="password">
        <i class="bi bi-eye-slash" id="togglePassword"></i><br>
        <input type="submit" value="Log in" id="log-button">
    </form>
    <a href= "\register"> Not registred yet ? </a>
    <p <?php if(!isset($_GET['login'])){ echo "hidden";}?> style="color:red;"> Wrong name or username. </p>
</div>

<?php if (isset($_GET['login'])) { 
            session_start(); ?> 
            <!-- Fill input fields once again with content before error   -->
            <script > 
            document.getElementById("email").value = "<?php echo $_SESSION["email"]?>";
            document.getElementById("password").value = "<?php echo $_SESSION["password"]?>";
            </script>

<?php       
            unset($_SESSION["email"]);
            unset($_SESSION["password"]);
} ?>

<script>
    document.getElementById("email").required = true;
    document.getElementById("password").required = true;

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