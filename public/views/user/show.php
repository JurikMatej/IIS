<!-- GET /users/{user} -->
<!-- Singular user's details -->

<div class="user-detail">
     <h1> <?=$user->getFirstName()?> <?=$user->getLastName()?> </h1>
     <p><?=$user->getMail()?></p>
     <p><?=$user->getPassword()?></p>
     <p><?=$user->getAddress()?></p>
     <p><?=$user->getFormattedRegisteredSince()?></p>
     <p><?=$user->getRole()?></p>
     <a href="<?=$user->getId()?>/edit">Edit</a>
     <a href="<?=$user->getID()?>/delete" onclick="return confirm('Are you sure you want to delete user <?=$user->getFirstName()?> <?=$user->getLastName()?> ?')">Delete</a>
     <?php 
          //session_start();
          $user_mail = (isset($_SESSION['email']))? $_SESSION['email']: "";
          $user_role = (isset($_SESSION['role']))? $_SESSION['role']: "";
          
          // enable to see all users only to admins
          if ($user_role === 'Admin') {

               $name = $_SERVER["SERVER_NAME"];
               $port = ':'.$_SERVER["SERVER_PORT"];

     ?>
     <a href="<?php $name.$port?>/users">See all users</a><br>

     <?php } ?>
</div>

<!-- id" => $this->id,
"first_name" => $this->first_name,
"last_name" => $this->last_name,
"mail" => $this->mail,
"password" => $this->password,
"address" => $this->address,
"registered_since" => $this->getFormattedRegisteredSince(),
"role_id" => $this->role_id,
"role" => $this->role,
"authority_level" => $th -->
