<?php

namespace App\Model;

use App\Model\Entities\User;
use Nette\Security\Passwords;

class UserModel extends BaseModel
{

    /**
     * Registers a new guest user with given email and password
     *
     * @param $values
     * @return User|null
     */
    public function createUser($values)
    {
        $user = $this->getOne(User::class, array("email" => $values["email"]));
        if($user){
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

}