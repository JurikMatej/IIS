<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class LogoutUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        if(!isset($_SESSION)) session_start();
        if (isset($_SESSION['email'])) unset($_SESSION['email']);
        if (isset($_SESSION['password'])) unset($_SESSION['password']);
        if (isset($_SESSION['role'])) unset($_SESSION['role']);
        if (isset($_SESSION['id']))   unset($_SESSION['id']);

        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        header("Location: http://$name$port");
        exit();
    
        return $this->response;
    }
}