<?php

namespace App\Components;

use App\Model\Entities\User;
use App\Model\UserModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Tracy\Debugger;

class PasswordChangeForm extends Control{

    /** @var UserModel */
    public $userModel;

    public $id;


    public function __construct(UserModel $userModel)
    {
        parent::__construct();
        $this->userModel= $userModel;
    }

    public function render($id)
    {
        $user = $this->userModel->getOne(User::class, array("id" => $id));

        if (isset($user)) {
            $this->template->userChange = $user;
        }

        if ($id == $this->presenter->getUser()->getId()) {
            $this->template->self = TRUE;
        } else {
            $this->template->self = FALSE;
        }

        $this->id = $id;

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
        $form->addHidden("id", "id");

        if ($this->id) {
            $form->setDefaults(array("id" => $this->id));
        }

        $form->addSubmit("submit", "Změnit heslo");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();
        $currentUser = $this->userModel->getOne(User::class, array("id" => $this->presenter->getUser()->getId()));
        $user = $this->userModel->getOne(User::class, array("id" => $values["id"]));

        if (!$currentUser){
            $this->presenter->flashMessage("Přihlášený uživatel nebyl nalezen", "error");
            return;
        }

        if ($user == $currentUser) {

            if (!Passwords::verify($values["oldpass"], $currentUser->getPassword())) {
                $this->presenter->flashMessage("Vaše staré heslo nebylo zadáno správně", "error");
                return;
            } elseif ($values["newpass"] != $values["newpasscheck"]) {
                $this->presenter->flashMessage("Zadaná nová hesla se neshodují", "error");
                return;
            }

            $currentUser->setPassword(Passwords::hash($values["newpass"]));
            $this->userModel->flush();
            $this->presenter->flashMessage("Vaše heslo bylo úspěšně změněno", "success");

        } else {

            if (!$this->presenter->getUser()->isInRole("admin")) {
                $this->presenter->flashMessage("Pro tuto akci nemáte dostatečná oprávnění!", "error");
            }

            if (!Passwords::verify($values["oldpass"], $currentUser->getPassword())) {
                $this->presenter->flashMessage("Vaše heslo nebylo zadáno správně", "error");
                return;
            } elseif ($values["newpass"] != $values["newpasscheck"]) {
                $this->presenter->flashMessage("Zadaná nová hesla se neshodují", "error");
                return;
            }

            $user->setPassword(Passwords::hash($values["newpass"]));
            $this->userModel->flush();
            $this->presenter->flashMessage("Heslo uživatele " . $user->getEmail() .  " bylo úspěšně změněno", "success");

        }


    }

}

interface IPasswordChangeFormFactory
{
    /** @return PasswordChangeForm */
    function create();
}