<?php

namespace App\Model\Mailer;

use App\Model\Entities\User;

interface IMailer
{
    public function sendMail(User $user);
}
