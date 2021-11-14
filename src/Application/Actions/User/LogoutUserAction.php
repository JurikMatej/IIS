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
        session_start();
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        if (isset($_SESSION['role'])) unset($_SESSION['role']);

        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        header("Location: http://$name$port");
        exit();
    
        return $this->response;
    }
}