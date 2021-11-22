<!-- GET /users/{user}/edit -->
<!-- Show form to edit singular user's details -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="user-edit">
    <h3 style="margin-bottom: 30px;">Edit user</h3>
    <form action="update" method="post">
    <label for="name"> Name:</label> <input type="text" name="name" value=<?=$user->getFirstName()?>  ><br>
    <label for="surname"> Surname:</label> <input type="text" name="surname" value=<?=$user->getLastName()?>  ><br>
    <label for="email"> E-mail:</label> <input type="text" name="email" value=<?=$user->getMail()?> ><br>
    <label for="password"> Password:</label> <input type="password" name="password" value=<?=$user->getPassword()?> ><br>
    <label for="address"> Address:</label> <input type="text" name="address" value=<?=$user->getAddress()?> ><br>
    <label for="role"> Role:</label> 
    <select name="role"> 
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
