<?php

namespace App\Model\Mailer;

use App\Model\Entities\User;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

class RegistrationMailer implements IMailer
{

    public function sendMail(User $user)
    {
        $mail = new Message();

        $mail->setFrom("codebook@gym-karvina.cz", "Codebook");
        $mail->addTo($user->getEmail());
        $mail->setSubject("Vítejte na Codebooku!");

        $mail->setHtmlBody("<p>Dobrý den,</p><p>právě jsme pro Vás vytvořili účet na stránce http://codebook.gym-karvina.cz/. Abyste mohli upravovat obsah webu, je nutné, aby Vám učitel Váš účet aktivoval. </p>");

        $mailer = new SendmailMailer();

        $mailer->send($mail);
    }
}