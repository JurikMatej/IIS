<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

// TODO Actions are the C of MVC - Action base class has utility methods, ModelAction adds protected modelRepository
//      and concrete action dispatcher lies in separate conviniently named file (e.g. ListUsersAction.php) where the
//      controller functionality is defined per file (each file is a class that implements the Action->action() method)
//      
// TODO Actions/Controllers are assigned to specific views in ~/app/routes.php

abstract class UserAction extends Action
{
    /**
     * @var UserRepository
     */
    protected $userRepository;


    /**
     * @var PhpRenderer
     */
    protected $userViewRenderer;


    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     */
    public function __construct(LoggerInterface $logger,
                                UserRepository $userRepository
    ) {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->userViewRenderer = new PhpRenderer("views/user");
    }
}
