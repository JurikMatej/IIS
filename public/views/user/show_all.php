
<?php require_once "templates/user-operations.inc.php";?>

<div class="users-wrapper" style="text-align: center">

    <?php foreach ($users as $user): ?>

        <div class="user-component" data-id="<?= $user->getId() ?>">
            <h3>
                <a href="/users/<?= $user->getId() ?>">
                    <?php
                    echo $user->getFirstName();
                    echo " ";
                    echo $user->getLastName();
                    ?>
                </a>
            </h3>

            <p><?php echo $user->getRole();?></p>
            <hr>
        </div>
    <?php endforeach; ?>
</div>
