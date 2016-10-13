<?php

namespace App\Model;

use App\Model\Entities\User;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;

class UserModel extends BaseModel
{

    public $roles = Array("guest", "editor", "admin");

    /**
     * Registers a new guest user with given e-mail and password
     *
     * @param $values
     * @return User|null
     */
    public function createUser($values)
    {
        $user = $this->getOne(User::class, array("email" => $values["email"]));

        if ($user) {
            return null;
        }

        $user = new User();
        $user->setEmail($values["email"]);
        $user->setPassword(Passwords::hash($values["password"]));
        $user->setRole("guest");

        $this->persist($user);
        $this->flush();

        return $user;
    }

    /**
     * @param $email
     * @return User
     */
    public function getUserByEmail($email)
    {
        $user = $this->getOne(User::class, array("email" => $email));

        return $user;
    }

}