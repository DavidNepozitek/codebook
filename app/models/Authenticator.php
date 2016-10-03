<?php

namespace App\Model;

use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

class Authenticator extends Object implements IAuthenticator
{

    /** @var UserModel */
    private $userModel;


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
            throw new AuthenticationException('Uživatel s tímto e-mailem není zaregistrován.', self::IDENTITY_NOT_FOUND);
        } elseif (!Passwords::verify($password, $user->getPassword())) {
            throw new AuthenticationException('Zadané heslo nesouhlasí.', self::INVALID_CREDENTIAL);
        } elseif ($user->getRole() == "guest") {
            throw new AuthenticationException("Váš účet ještě není aktivován. O aktivaci požádejte učitele.");
        }

        $arr = array(
            "id" => $user->getId(),
            "email" => $user->getEmail(),
        );

        $roles[] = $user->getRole();

        return new Identity($user->getId(), $roles, $arr);
    }

}