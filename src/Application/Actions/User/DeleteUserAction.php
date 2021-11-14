<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\User\User;

class DeleteUserAction extends UserAction {

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $this->userRepository->delete($userId);
        $this->logger->info("User of id `${userId}` was deleted.");

        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];

        header("Location: http://$name$port/users");
        exit(); 

        return $this->response;
    }
}