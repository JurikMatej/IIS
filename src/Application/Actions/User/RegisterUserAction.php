<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\User\User;
use DateTime;

class RegisterUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $mail=(isset($_POST['email']))?$_POST['email']:'';
        $password=(isset($_POST['password']))?$_POST['password']:'';
        $first_name=(isset($_POST['first_name']))?$_POST['first_name']:'';
        $last_name=(isset($_POST['last_name']))?$_POST['last_name']:'';
        $address=(isset($_POST['address']))?$_POST['address']:'';

        $users = $this->userRepository->findAll();
        
        $name = $_SERVER["SERVER_NAME"];
        $port = ':'.$_SERVER["SERVER_PORT"];
      

        // check if visitor set all labels in registration form
        if ($mail == '' || $password == '' || $first_name == '' || $last_name == '' || $address == '') {
            header("Location: http://$name$port/register" . "?non_empty_register=failed");
            exit();
            return $this->response;
        }

        // check if email is not already set
        foreach ($users as $user)
        {
            if ($mail === $user->getMail())
            {
                header("Location: http://$name$port/register" . "?mail_register=failed");
                exit();
                return $this->response;
            }
        }

        // create new instance of user a insert him to DB
        $new_user = User::create()
            ->setFirstName($first_name)
            ->setLastName($last_name)
            ->setAddress($address)
            ->setPassword($password)
            ->setMail($mail)
            ->setRole("User")
            ->setRegisteredSince(new DateTime('now'))
            ->setRoleId(3)
            ->setAuthorityLevel(2);

        $this->userRepository->save($new_user);
        
        // transport to home page
        header("Location: http://$name$port"."/home");
        
        exit();

        return $this->response; // TODO not providing
    }
}