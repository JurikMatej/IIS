
<?php require_once "templates/user-operations.inc.php";?>

<?php foreach ($users as $user): ?>

<div class="user-list">
    <h3><a href="/users/<?php echo $user->getId()?>"> <?php echo $user->getFirstName();echo " "; echo $user->getLastName();?> </a></h3>
    <p><?php echo $user->getRole();?></p>
    <hr>
</div>
<?php endforeach; ?>
