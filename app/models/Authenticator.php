<?php

namespace App\Model;

use Kdyby\Events\Event;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

class Authenticator extends Object implements IAuthenticator
{

    /** @var UserModel */
    private $userModel;

    /** @var array|Event */
    public $onSuccess = [];

    /** @var array|Event */
    public $onError = [];



    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Authenticates a user with e-mail and password
     *
     * @param array $credentials
     * @return Identity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            $this->onError($email, self::IDENTITY_NOT_FOUND);
            throw new AuthenticationException('Uživatel s tímto e-mailem není zaregistrován.', self::IDENTITY_NOT_FOUND);
        } elseif (!Passwords::verify($password, $user->getPassword())) {
            $this->onError($email, self::INVALID_CREDENTIAL);
            throw new AuthenticationException('Zadané heslo nesouhlasí.', self::INVALID_CREDENTIAL);
        } elseif ($user->getRole() == "guest") {
            $this->onError($email, self::NOT_APPROVED);
            throw new AuthenticationException("Váš účet ještě není aktivován. O aktivaci požádejte učitele.");
        }

        $arr = array(
            "id" => $user->getId(),
            "email" => $user->getEmail(),
        );

        $roles[] = $user->getRole();

        $this->onSuccess($email);

        return new Identity($user->getId(), $roles, $arr);
    }

}