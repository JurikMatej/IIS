<!-- GET /users/{user}/edit -->
<!-- Show form to edit singular user's details -->

<form action="update" method="post">
Name: <input type="text" name="name" value=<?=$first_name?>  ><br>
Surname: <input type="text" name="surname" value=<?=$last_name?>  ><br>
E-mail: <input type="text" name="email" value=<?=$mail?> ><br>
Password: <input type="password" name="password" value=<?=$password?> ><br>
Address: <input type="text" name="address" value=<?=$address?> ><br>
Role: 
<select name="role"> 
    <?php foreach ($roles as $option): ?>
    <?php if ('Visitor'=== $option->role): ?>
        <?php  continue;?>
    <?php endif; ?>
    <option 
        <?php if ($role === $option->role): ?>
            <?php  echo 'selected="selected"'?>
        <?php endif; ?>
        
    > <?php echo $option->role?></option>
    <?php endforeach; ?>
    </select>
</select>
<input type="submit">
</form>
