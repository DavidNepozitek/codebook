<?php

namespace App\Model;

use App\Model\Entities\User;

class UserModel extends BaseModel
{

    public $roles = Array("guest", "editor", "admin");

    /**
     * Gets a user by e-mail
     *
     * @param $email
     * @return User
     */
    public function getUserByEmail($email)
    {
        $user = $this->getOne(User::class, array("email" => $email));

        return $user;
    }

}