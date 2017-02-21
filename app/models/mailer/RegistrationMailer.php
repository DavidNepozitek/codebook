<?php

namespace App\Model\Mailer;

use App\Model\Entities\User;
use Latte\Engine;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class RegistrationMailer implements IMailer
{

    public function sendMail(User $user)
    {
        $mail = new Message();
        
        $latte = new Engine();

        $mail->setFrom("codebook@gym-karvina.cz", "Codebook");
        $mail->addTo($user->getEmail());
        $mail->setSubject("Vítejte na Codebooku!");

        $mail->setHtmlBody($latte->renderToString("app/MailTemplates/RegistrationMail.latte"));

        $mailer = new SendmailMailer();

        $mailer->send($mail);
    }
}
