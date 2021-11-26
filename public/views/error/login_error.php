<!--User did not login-->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<?php $server = 'http://'.$_SERVER["SERVER_NAME"]; $port = ':'.$_SERVER["SERVER_PORT"];?>

<div class="login-error">
    <h1 style="background-color: red;">You have to log in or register first !</h1>
    <a href="<?=$server.$port.'/login'?>" class="btn btn-primary">Log in</a>
    <a href="<?=$server.$port.'/register'?>" class="btn btn-primary">Not registered yet ?</a>
</div>


<?php require_once "templates/footer.inc.php";?>