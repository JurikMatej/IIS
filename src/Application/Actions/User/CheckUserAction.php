<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class CheckUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $login=(isset($_POST['email']))?$_POST['email']:'';
        $password=(isset($_POST['password']))?$_POST['password']:'';
        $users = $this->userRepository->findAll();
        $dest = "/";
        $failed = true;
        foreach ($users as $user)
        {
            if ($login === $user->getMail() && $password === $user->getPassword())
            {
                $id = $user->getId();
                $this->logger->info("User `${id}` has logged in.");

                session_start();
                $_SESSION['user'] = $login;
                $_SESSION['role'] = $user->getRole();

                $dest = "/users/" . $id;
                $failed = false;

            }
        }

        $script = $_SERVER["PHP_SELF"];
        if (strpos($dest, '/') === 0) 
        {
            $path = $dest;
        } 
        else
        {
            $path = substr($script, 0,
            strrPos($script, "/"))."/$dest";
        }
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];
        if ($failed)
        {
            header("Location: http://$name$port$path" . "?login=failed");
        }
        else
        {
            header("Location: http://$name$port$path");
        }
        
        exit();

        return $this->response; // TODO not providing
    }
}