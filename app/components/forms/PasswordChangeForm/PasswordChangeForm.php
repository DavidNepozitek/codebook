<?php

namespace App\Components;

use App\Model\Entities\User;
use App\Model\UserModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;

class PasswordChangeForm extends Control{

    /** @var UserModel */
    public $userModel;

    public function __construct(UserModel $userModel)
    {
        parent::__construct();
        $this->userModel= $userModel;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . "/PasswordChangeForm.latte");
        $template->render();
    }

    protected function createComponentForm()
    {
        $form = new Form();

        $form->addPassword("oldpass")
            ->setAttribute("placeholder", "Aktuální heslo")
            ->setRequired("Vyplňte prosím své aktuální heslo");
        $form->addPassword("newpass")
            ->setAttribute("placeholder", "Nové heslo")
            ->setRequired("Vyplňte prosím své nové heslo");
        $form->addPassword("newpasscheck")
            ->setAttribute("placeholder", "Nové heslo znovu")
            ->setRequired("Vyplňte prosím své nové heslo ještě jednou pro kontrolu");

        $form->addSubmit("submit", "Změnit heslo");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();
        $currentUser = $this->userModel->getOne(User::class, array("id" => $this->presenter->getUser()->getIdentity()->getId()));

        if (!$currentUser){
            $this->flashMessage("Přihlášený uživatel nebyl nalezen", "error");
            return;
        }

        if (!Passwords::verify($values["oldpass"], $currentUser->getPassword())) {
            $this->flashMessage("Vaše staré heslo nebylo zadáno správně", "error");
            return;
        } elseif ($values["newpass"] != $values["newpasscheck"]) {
            $this->flashMessage("Zadaná nová hesla se neshodují", "error");
            return;
        }

        $currentUser->setPassword(Passwords::hash($values["newpass"]));
        $this->userModel->flush();
        $this->flashMessage("Vaše heslo bylo úspěšně změněno", "success");
        $this->presenter->redirect("this");
    }

}

interface IPasswordChangeFormFactory
{
    /** @return PasswordChangeForm */
    function create();
}