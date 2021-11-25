<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class EditUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        if (!isset($_SESSION)) session_start();
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];
        
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        $userId = (int) $this->resolveArg('id');

        // editation of user from URL can me made only by logged user and admin
        if ($_SESSION['id'] !== $userId && $_SESSION['role'] !== "Admin")
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }

        $user = $this->userRepository->findUserOfId($userId);
        $user_roles = $this->userRepository->getUserRoles();


        $this->logger->info("User of id `${userId}` is being edited.");

        $this->userViewRenderer->setLayout("index.php");
        $this->userViewRenderer->render($this->response, "edit.php", ["user" => $user, "roles" => $user_roles]);
        
        return $this->response;
    }
}
