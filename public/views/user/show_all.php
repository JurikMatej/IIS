
<?php foreach ($users as $user): ?>

<h3><a href="/users/<?php echo $user->getId()?>"> <?php echo $user->getFirstName();echo " "; echo $user->getLastName();?> </a></h3>
<p><?php echo $user->getRole();?></p>
<hr>

<?php endforeach; ?>
