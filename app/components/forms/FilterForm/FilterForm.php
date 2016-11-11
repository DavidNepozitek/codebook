<?php

namespace App\Components;

use App\Model\TutorialModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

class FilterForm extends Control{

    /** @var TutorialModel */
    private $tutorialModel;

    public function __construct(TutorialModel $tutorialModel)
    {
        parent::__construct();
        $this->tutorialModel = $tutorialModel;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . "/FilterForm.latte");
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

        $form->addSubmit("submit", "Přihlásit se");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        try {
            $this->presenter->getUser()->login($values["email"], $values["password"]);
            $this->presenter->getUser()->setExpiration(0, TRUE);
            $this->presenter->redirect("Dashboard:default");
        } catch (AuthenticationException $e) {
            $this->flashMessage($e->getMessage(), "error");
        }
    }

}

interface IFilterFormFactory
{
    /** @return FilterForm */
    function create();
}