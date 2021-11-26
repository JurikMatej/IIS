<!-- GET /users/{user}/edit -->
<!-- Show form to edit singular user's details -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="user-edit">
    <h3 style="margin-bottom: 30px;">Edit user</h3>
    <form action="update" method="post">
    <label for="name"> Name:</label> <input type="text" name="name" id= "name" value=<?=$user->getFirstName()?>  required><br>
    <label for="surname"> Surname:</label> <input type="text" name="surname" id= "surname" value=<?=$user->getLastName()?>  required><br>
    <label for="email"> E-mail:</label> <input type="text" name="email" id="email" value=<?=$user->getMail()?> required><br>
    <label for="password"> Password:</label> <input type="password" name="password" id="password" required value=<?=$_SESSION['password']?> >
    <i class="bi bi-eye-slash" id="togglePassword"></i><br>
    <label for="address"> Address:</label> <input type="text" name="address" id="address" value=<?=$user->getAddress()?> ><br>
    <label for="role" id="label_role" hidden> Role:</label> 
    <select name="role" id="role" hidden> 
        <?php foreach ($roles as $option): ?>
        <?php if ('Visitor'=== $option->role): ?>
            <?php  continue;?>
        <?php endif; ?>
        <option 
            <?php if ($user->getRole() === $option->role): ?>
                <?php  echo 'selected="selected"'?>
            <?php endif; ?>
            
        > <?php echo $option->role?></option>
        <?php endforeach; ?>
        </select>
    </select>
    <input type="submit" value="Edit user">
    </form>
</div>

<?php if ($_SESSION['role'] == "Admin" && $_SESSION['role'] != $user->getRole()) { ?>
    <script>
        document.getElementById('name').disabled = true;
        document.getElementById('surname').disabled = true;
        document.getElementById('email').disabled = true;
        document.getElementById('password').disabled = true;
        document.getElementById('address').disabled = true;
        document.getElementById('togglePassword').hidden = "true";

        document.getElementById('role').hidden = false;
        document.getElementById('label_role').hidden = false;
    </script>
<?php } ?>

<script>

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
