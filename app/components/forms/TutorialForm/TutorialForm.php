<?php

namespace App\Components;

use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;
use Tracy\Debugger;

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

    //TODO: Required content

    protected function createComponentForm()
    {
        $form = new Form();

        $form->addText("title")
            ->setAttribute("placeholder", "Název návodu")
            ->setRequired("Vyplňte prosím svůj email");
        $form->addSelect('difficulty', 'Obtížnost:', $this->tutorialModel->difficulties)
            ->setRequired("Zvolte prosím kateogrii")
            ->setPrompt('Zvolte obtížnost');
        $form->addText("tags")
            ->setAttribute("placeholder", "Štítky");
        $form->addTextArea("perex")
            ->setAttribute("placeholder", "Perex")
            ->setRequired("Vyplňte prosím perex");
        $form->addTextArea("source")
            ->setAttribute("placeholder", "Obsah článku");
        $form->addCheckbox('published', 'Publikovat ihned');

        $form->addInteger("id", "id");

        if ($this->tutorial) {

            foreach ($this->tutorial->getTags()->toArray() as $tag) {
                $tags[] = $tag->getName();
            }


            $form->setDefaults(array(
                "title" => $this->tutorial->getTitle(),
                "perex" => $this->tutorial->getPerex(),
                "source" => $this->tutorial->getSource(),
                "difficulty" => $this->tutorial->getDifficulty(),
                "published" => $this->tutorial->getPublished(),
                "id" => $this->tutorial->getId(),

            ));

            if (isset($tags)) {
                $tags = json_encode($tags);
                $form->setDefaults(array("tags" => $tags));
            }

        }

        $form->addSubmit("submit", "Přidat článek");

        $form->onSuccess[] = array($this, "processForm");


        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        if ($values["id"]) {
            try {
                $this->tutorialModel->editTutorial(
                    $values["id"], $values["title"], $values["perex"], $values["source"],
                    $values["difficulty"], $values["published"], $values["tags"]
                );
                $this->flashMessage("Článek byl úspěšně upraven", "success");
            } catch (Exception $e) {
                $this->flashMessage($e->getMessage(), "error");
            }

        } else {
            try {
                $this->tutorialModel->createTutorial(
                    $values["title"], $values["perex"], $values["source"],$values["difficulty"], $values["published"],
                    $values["tags"]
                );
                $this->flashMessage("Nový článek byl úspěšně přidán", "success");
                $this->presenter->redirect("this");
            } catch (Exception $e) {
                $this->flashMessage($e->getMessage(), "error");
            }
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