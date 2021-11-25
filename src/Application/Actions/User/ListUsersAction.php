<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
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

        if ($_SESSION['role'] !== "Admin")
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }

        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        $this->userViewRenderer->setLayout("index.php");
        
        $this->userViewRenderer->render($this->response,"show_all.php", ["users" => $users]);
        
        return $this->response;
        // return $this->respondWithData($users);
    }
}
