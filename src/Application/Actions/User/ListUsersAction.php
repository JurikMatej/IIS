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
        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        $this->userViewRenderer->setLayout("index.php");
        
        $this->userViewRenderer->render($this->response,"show_all.php", ["users" => $users]);
        
        return $this->response;
        // return $this->respondWithData($users);
    }
}
