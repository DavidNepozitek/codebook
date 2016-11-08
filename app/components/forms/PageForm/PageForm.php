<?php

namespace App\Components;

use App\Model\Entities\Page;
use App\Model\PageModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;


class PageForm extends Control
{

    private $name;
    private $page;

    /** @var  PageModel */
    private $pageModel;

    public function __construct(PageModel $pageModel)
    {
        parent::__construct();
        $this->pageModel = $pageModel;
    }

    public function render($name)
    {
        if ($name) {
            $this->page = $this->pageModel->getOne(Page::class, array("name" => $name));
            $this->name = $name;
        }

        $template = $this->template;
        $template->setFile(__DIR__ . "/PageForm.latte");
        $template->render();
    }


    protected function createComponentForm()
    {
        $form = new Form();

        $form->addText("title")
            ->setAttribute("placeholder", "Nadpis stránky")
            ->setRequired("Vyplňte prosím nadpis stránky");
        $form->addTextArea("source")
            ->setAttribute("placeholder", "Obsah článku");

        $form->addHidden("name", "name");

        if ($this->name) {
            $form->setDefaults(array(
                "name" => $this->name
            ));

            if ($this->page) {
                $form->setDefaults(array(
                    "title" => $this->page->getTitle(),
                    "source" => $this->page->getSource()
                ));
            }
        }

        $form->addSubmit("submit", "Upravit stránku");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        try {
            $this->pageModel->editPage($values["name"], $values["title"], $values["source"]);
            $this->presenter->flashMessage("Stránka \"" . $values["title"]. "\" byla úspěšně upravena!", "success");
        } catch (Exception $e) {

        }
        
    }

}

interface IPageFormFactory
{
    /**
     * @return PageForm
     */
    function create();
}