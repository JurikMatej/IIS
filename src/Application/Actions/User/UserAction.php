<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

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
