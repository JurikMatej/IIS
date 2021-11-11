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
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        $this->logger->info("User of id `${userId}` was viewed.");

        $this->userViewRenderer->setLayout("index.php");
        $this->userViewRenderer->render($this->response, "show.php", ["user" => $user]);
        
        return $this->response;
        // return $this->respondWithData($user);
    }
}
