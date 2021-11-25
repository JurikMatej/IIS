<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id']))
        {
            $dest = "/error" ;
            header("Location: http://$name$port$dest");
            exit();
        }

        $userId = (int) $this->resolveArg('id');

        // logged user can see only his profile view 
        if ($userId !== $_SESSION['id'] && $_SESSION['role'] !== "Admin")
        {
            $dest = "/unauthorized_access_error";
            header("Location: http://$name$port$dest");
            exit();
        }
        $user = $this->userRepository->findUserOfId($userId);

        $this->logger->info("User of id `${userId}` was viewed.");

        $this->userViewRenderer->setLayout("index.php");
        $this->userViewRenderer->render($this->response, "show.php", ["user" => $user]);
        
        return $this->response;
        // return $this->respondWithData($user);
    }
}
