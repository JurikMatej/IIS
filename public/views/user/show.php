<!-- GET /users/{user} -->
<!-- Singular user's details -->


<h1> <?=$user->getFirstName()?> <?=$user->getLastName()?> </h1>
<p><?=$user->getMail()?></p>
<p><?=$user->getPassword()?></p>
<p><?=$user->getAddress()?></p>
<p><?=$user->getFormattedRegisteredSince()?></p>
<p><?=$user->getRole()?></p>
<a href="<?=$user->getId()?>/edit">Edit</a>


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
