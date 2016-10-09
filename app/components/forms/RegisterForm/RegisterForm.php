<?php

namespace App\Components;

use App\Model\UserModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class RegisterForm extends Control
{

    /** @var UserModel */
    public $userModel;

    public function __construct(UserModel $userModel)
    {
        parent::__construct();
        $this->userModel = $userModel;
    }

    public function render($registered = false)
    {
        $template = $this->template;

        $template->registered = $registered;
        $template->setFile(__DIR__ . "/RegisterForm.latte");
        $template->render();
    }

    protected function createComponentForm()
    {
        $form = new Form();

        $form->addText("email")
            ->setType("email")
            ->setAttribute("placeholder", "E-mail")
            ->setRequired("Vyplňte prosím svůj email");
        $form->addPassword("password")
            ->setAttribute("placeholder", "Heslo")
            ->setRequired("Vyplňte prosím své heslo");

        $form->addSubmit("submit", "Registrovat se");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        $user = $this->userModel->createUser($values);

        if ($user) {
            $this->presenter->redirect("this", array("registered" => true));
        } else {
            $this->flashMessage("Uživatel s tímto e-mailem již existuje.", "error");
        }
    }

}

interface IRegisterFormFactory
{
    /** @return RegisterForm */
    function create();
}