<?php

namespace App\Models\Log;

use App\Model\Entities\User;

class RegistrationLog
{

    /** @var ILogger  */
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
}