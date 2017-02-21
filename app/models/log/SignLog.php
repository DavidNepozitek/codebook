<?php

namespace App\Model\Log;

use App\Model\Entities\User;

class SignLog
{

    /**
* 
     *
 * @var ILogger  
*/
    private $logger;

    public function __construct(FileLogger $logger)
    {
        $this->logger = $logger;
    }

    public function logRegistration(User $user)
    {
        $message = "Account for user ". $user->getEmail() . " was created";
        $this->logger->log($message);
    }

    public function logSignIn($email, $error = 0)
    {
        switch ($error) {
        case 0:
            $message = "User ". $email . " signed in";
            break;
        case 1:
            $message = "Someone tried to log as ". $email . ", but this identity was not found";
            break;
        case 2:
            $message = "Someone tried to log as ". $email . " with wrong password";
            break;
        case 4:
            $message = "User ". $email . " tried to log with guest role";
            break;
        default:
            $message = "Someone tried to log as ". $email . " signed in";
        }
        
        $this->logger->log($message);
    }
}
