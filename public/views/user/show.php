<!-- GET /users/{user} -->
<!-- Singular user's details -->


<h1> <?=$first_name?> <?=$last_name?> </h1>
<p><?=$mail?></p>
<p><?=$password?></p>
<p><?=$address?></p>
<p><?=$registered_since?></p>
<p><?=$role?></p>
<a href="<?=$userID?>/edit">Edit</a>


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
