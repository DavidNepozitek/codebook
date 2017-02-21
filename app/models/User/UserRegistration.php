<?php

namespace App\Model\User;

use App\Model\BaseModel;
use App\Model\Entities\User;
use Kdyby\Events\Event;
use Nette\Security\Passwords;

class UserRegistration extends BaseModel
{

    /**
     * @var array|Event
     */
    public $onSuccess = [];

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

        $this->onSuccess($user);

        return $user;
    }

}
