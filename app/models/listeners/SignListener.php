<?php

namespace App\Model\Listeners;

use App\Model\Authenticator;
use App\Model\ConfigModel;
use App\Model\Entities\User;
use App\Model\Log\SignLog;
use App\Model\User\UserRegistration;
use App\Model\Mailer\RegistrationMailer;
use Kdyby\Events\Subscriber;
use Nette\Object;

class SignListener extends Object implements Subscriber
{

    /** @var  SignLog */
    private $signLog;

    /** @var  RegistrationMailer */
    private $registrationMailer;

    /** @var ConfigModel  */
    private $configModel;

    public function __construct(SignLog $signLog, RegistrationMailer $registrationMailer, ConfigModel $configModel)
    {
        $this->signLog = $signLog;
        $this->registrationMailer = $registrationMailer;
        $this->configModel = $configModel;
    }

    public function getSubscribedEvents()
    {
        return array(
            UserRegistration::class . '::onSuccess' => 'onRegistrationSuccess',
            Authenticator::class . '::onSuccess' => 'onSignInSuccess',
            Authenticator::class . '::onError' => 'onSignInError',
        );
    }

    public function onRegistrationSuccess(User $user)
    {
        $this->signLog->logRegistration($user);

        if ($this->configModel->getSection("mailing")["onRegistration"]) {
            $this->registrationMailer->sendMail($user);
        }
    }

    public function onSignInSuccess($email)
    {
        $this->signLog->logSignIn($email);
    }

    public function onSignInError($email, $error)
    {
        $this->signLog->logSignIn($email, $error);
    }
}