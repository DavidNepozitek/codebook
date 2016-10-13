<?php

namespace App\Components;

use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;

class TutorialForm extends Control{

    /** @var TutorialModel */
    public $tutorialModel;

    private $id;

    private $tutorial;

    /**
     * TutorialForm constructor.
     * @param $id
     * @param TutorialModel $tutorialModel
     */
    public function __construct($id, TutorialModel $tutorialModel)
    {
        parent::__construct();
        $this->tutorialModel= $tutorialModel;
        $this->id = $id;

        if ($this->id) {
            $this->tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $this->id));
        }
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
        $form->addSelect('difficulty', 'Obtížnost:', $this->tutorialModel->difficulties)
            ->setPrompt('Zvolte obtížnost');
        $form->addTextArea("perex")
            ->setAttribute("placeholder", "Perex")
            ->setRequired("Vyplňte prosím perex");
        $form->addTextArea("source")
            ->setAttribute("placeholder", "Obsah článku")
            ->setRequired("Návod musí obsahovat nějaký text");
        $form->addCheckbox('published', 'Publikovat ihned');

        $form->addSubmit("submit", "Přidat článek");

        $form->onSuccess[] = array($this, "processForm");

        if ($this->tutorial) {

            $form->setDefaults(array(
                "title" => $this->tutorial->getTitle(),
                "perex" => $this->tutorial->getPerex(),
                "source" => $this->tutorial->getSource(),
                "difficulty" => $this->tutorial->getDifficulty(),
                "published" => $this->tutorial->getPublished(),
            ));

            echo $this->tutorial->getTitle();
        }

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        try {
            $this->tutorialModel->createTutorial(
                $values["title"], $values["perex"], $values["source"],$values["difficulty"], $values["published"]
            );
            $this->flashMessage("Nový článek byl úspěšně přidán", "success");
            $this->presenter->redirect("this");
        } catch (Exception $e) {
            $this->flashMessage($e->getMessage(), "error");
        }

    }

}

interface ITutorialFormFactory
{
    /**
     * @param $id
     * @return TutorialForm
     */
    function create($id);
}