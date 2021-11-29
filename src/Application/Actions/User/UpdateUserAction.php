<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            $script = $_SERVER["PHP_SELF"];
            if (strpos($dest, '/') === 0) { // absolute path
                $path = $dest;
            } else {
                $path = substr($script, 0,
                strrPos($script, "/"))."/$dest";
            }
            $name = $_SERVER["SERVER_NAME"];
            $port = ':'.$_SERVER["SERVER_PORT"];
            header("Location: http://$name$port$path");
            exit();
        }

        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);
        $user_roles = $this->userRepository->getUserRoles();

        $this->logger->info("User of id `${userId}` was edited.");

        $level = "";
        $roleString  = "";
        $id    = 0;
        foreach ($user_roles as $role)
        {
            if ($role->role == $_POST["role"])
            {
                $level = $role->authority_level;
                $roleString = $role->role;
                $id = $role->id;
            }
        }

        $user->setRoleId(intval($id));
        $user->setRole($roleString);
        $user->setAuthorityLevel(intval($level));
        if ($_SESSION['role'] !== "Admin")
        {
            $user->setAddress($_POST["address"]);
            $user->setPassword(password_hash($_POST["password"], PASSWORD_DEFAULT));
            $user->setMail($_POST["email"]);
            $user->setLastName($_POST["surname"]);
            $user->setFirstName($_POST["name"]);
        }

        $this->userRepository->save($user);

        $this->userViewRenderer->render($this->response, "update.php", ["user" => $user]);
        
        return $this->response;
    }
}
