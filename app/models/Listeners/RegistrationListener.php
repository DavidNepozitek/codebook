<?php

namespace App\Models\Listeners;

use App\Model\Entities\User;
use App\Model\User\UserRegistration;
use App\Models\Log\RegistrationLog;
use Kdyby\Events\Subscriber;
use Nette\Object;

class RegistrationListener extends Object implements Subscriber
{


    /** @var  RegistrationLog */
    private $registrationLog;

    public function __construct(RegistrationLog $registrationLog)
    {
        $this->registrationLog = $registrationLog;
    }

    public function getSubscribedEvents()
    {
        return array(UserRegistration::class . '::onSuccess');
    }

    public function onSuccess(User $user)
    {
        $this->registrationLog->logRegistration($user);
    }
}