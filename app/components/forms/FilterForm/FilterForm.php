<?php

namespace App\Components;

use App\Model\Entities\Tag;
use App\Model\TutorialModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

class FilterForm extends Control{

    /** @var TutorialModel */
    private $tutorialModel;

    private $parameters;

    public function __construct(TutorialModel $tutorialModel)
    {
        parent::__construct();
        $this->tutorialModel = $tutorialModel;
    }

    public function render($search = NULL, $category = NULL, $difficulty = NULL)
    {
        $template = $this->template;
        $template->search = $search;
        $template->category = $category;
        $template->difficulty = $difficulty;

        $this->parameters = array("search" => $search, "category" => $category, "difficulty" => $difficulty);

        $template->setFile(__DIR__ . "/FilterForm.latte");
        $template->render();
    }

    protected function createComponentForm()
    {
        $form = new Form();

        foreach ($this->tutorialModel->getAll(Tag::class) as $tag) {
            $tags[$tag->getName()] = $tag->getName();
        }

        $difficulties = $this->tutorialModel->difficulties;
        $difficulties["-2"] = "Vše";

        $form->addText("search")
            ->setAttribute("placeholder", "Hledej...");
        $form->addSelect('category', 'Kategorie:', $tags)
            ->setPrompt('Kategorie');
        $form->addRadioList("difficulty", "Obtížnost", $difficulties);

        $form->setDefaults(array(
            "search" => $this->parameters["search"],
            "category" => $this->parameters["category"],
            "difficulty" => $this->parameters["difficulty"],
        ));

        $form->addSubmit("submit", "Přihlásit se");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        $this->presenter->redirect("default",
            array("search" => $values["search"], "category" => $values["category"],
                "difficulty" => $values["difficulty"]));
    }

}

interface IFilterFormFactory
{
    /** @return FilterForm */
    function create();
}