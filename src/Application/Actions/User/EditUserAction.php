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
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);
        $user_roles = $this->userRepository->getUserRoles();

        $arr = $user->jsonSerialize();
        $arr["roles"] = $user_roles;

        $this->logger->info("User of id `${userId}` is being edited.");

        $this->userViewRenderer->setLayout("index.php");
        $this->userViewRenderer->render($this->response, "edit.php", $arr);
        
        return $this->response;
    }
}
