<?php

namespace App\Components;

use App\Model\TutorialModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;

class TutorialForm extends Control{

    /** @var TutorialModel */
    public $tutorialModel;

    public function __construct(TutorialModel $tutorialModel)
    {
        parent::__construct();
        $this->tutorialModel= $tutorialModel;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . "/TutorialForm.latte");
        $template->render();
    }

    protected function createComponentForm()
    {
        $form = new Form();

        $form->addText("title")
            ->setAttribute("placeholder", "Název návodu")
            ->setRequired("Vyplňte prosím svůj email");
        $form->addSelect('difficulty', 'Obtížnost:')
            ->setPrompt('Zvolte obtížnost');
        $form->addTextArea("perex")
            ->setAttribute("placeholder", "Perex")
            ->setRequired("Vyplňte prosím perex");
        $form->addTextArea("source")
            ->setAttribute("placeholder", "Obsah článku")
            ->setRequired("Návod musí obsahovat nějaký text");

        $form->addSubmit("submit", "Přidat článek");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        try {
            $this->tutorialModel->createTutorial(
                $values["title"], $values["perex"], $values["source"],$values["difficulty"]
            );
        } catch (Exception $e) {
            $this->flashMessage($e->getMessage(), "error");
        }

    }

}

interface ITutorialFormFactory
{
    /** @return TutorialForm */
    function create();
}