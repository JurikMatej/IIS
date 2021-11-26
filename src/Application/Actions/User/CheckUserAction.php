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
        $failed = true;
        session_start();
        $_SESSION['email'] = $login;
        $_SESSION['password'] = $password;

        foreach ($users as $user)
        {
            if ($login === $user->getMail() && password_verify($password, $user->getPassword()))
            {
                $id = $user->getId();
                $this->logger->info("User `${id}` has logged in.");

                $_SESSION['role'] = $user->getRole();
                $_SESSION['id']   = $id;

                $failed = false;

            }
        }
        
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];
        if ($failed)
        {
            header("Location: http://$name$port/login" . "?login=failed");
        }
        else
        {
            // go to home page on success
            header("Location: http://$name$port");
        }
        
        exit();

        return $this->response; // TODO not providing
    }
}