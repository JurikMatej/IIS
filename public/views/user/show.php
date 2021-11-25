<!-- GET /users/{user} -->
<!-- Singular user's details -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="user-detail">
     <h1> <?=$user->getFirstName()?> <?=$user->getLastName()?> </h1>
     <p> Email: <?=$user->getMail()?></p>
     <p> Address: <?=$user->getAddress()?></p>
     <p> Date of registration: <?=$user->getFormattedRegisteredSince()?></p>
     <p> Your role: <?=$user->getRole()?></p>
     <a href="<?=$user->getId()?>/edit" class="btn btn-primary">Edit</a>
     <a href="<?=$user->getID()?>/delete" onclick="return confirm('Are you sure you want to delete user <?=$user->getFirstName()?> <?=$user->getLastName()?> ?')" class="btn btn-primary">Delete</a>
     <?php 
          $user_mail = (isset($_SESSION['email']))? $_SESSION['email']: "";
          $user_role = (isset($_SESSION['role']))? $_SESSION['role']: "";
          
     ?>

</div>