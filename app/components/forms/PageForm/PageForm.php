<?php

namespace App\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class PageForm extends Control
{
    

    public function render($name)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . "/PageForm.latte");
        $template->render();
    }


    protected function createComponentForm()
    {
        $form = new Form();

        $form->addText("title")
            ->setAttribute("placeholder", "Název návodu")
            ->setRequired("Vyplňte prosím svůj email");
        $form->addTextArea("source")
            ->setAttribute("placeholder", "Obsah článku");

        $form->addInteger("id", "id");

        $form->addSubmit("submit", "Přidat článek");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();
    }

}

interface IPageFormFactory
{
    /**
     * @return PageForm
     */
    function create();
}