<?php

namespace App\BackModule\Presenters;

use App\Components\ITutorialFormFactory;
use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Grido\DataSources\Doctrine;
use Grido\Grid;
use Nette\Utils\Html;

class TutorialPresenter extends BasePresenter
{

    /** @var  ITutorialFormFactory @inject */
    public $tutorialFormFactory;

    /** @var TutorialModel @inject */
    public $tutorialModel;

    private $tutorialId;

    protected function createComponentTutorialForm()
    {
        return $this->tutorialFormFactory->create($this->tutorialId);
    }

    public function renderEdit($id)
    {
        $this->tutorialId = $id;
    }

    protected function createComponentGrid($name)
    {
        $grid = new Grid($this, $name);

        $grid->setTemplateFile(__DIR__ . "/../templates/Grido/bootstrap.latte");

        $model = new Doctrine(
            $this->tutorialModel->getEm()->createQueryBuilder()
                ->select("t")->from(Tutorial::class, "t")
        );

        $grid->model = $model;

        $grid->addColumnText('id', 'ID')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('title', 'Název')
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnText('difficulty', 'Obtížnost')
            ->setReplacement($this->tutorialModel->difficulties)
            ->setSortable()
            ->setFilterText()
            ->setSuggestion();

        $grid->addColumnNumber('seenCount', 'Počet zhlédnutí')
            ->setSortable();

        $grid->addColumnText('published', 'Publikováno')
            ->setReplacement(array("Ne", "Ano"))
            ->setSortable();

        $grid->addColumnText('actions', 'Akce')
            ->setCustomRender(function ($tutorial) {

                $el = Html::el();

                $edit = Html::el("a");
                $edit->addText("Upravit");
                $edit->setAttribute("class", "btn btn--blue");
                $edit->href($this->link("Tutorial:edit", ["id" => $tutorial->getId()]));
                $editIcon = Html::el("i");
                $editIcon->addAttributes(array("class" => "fa fa-pencil"));
                $edit->addHtml($editIcon);

                $edit = Html::el("a");
                $edit->addText("Upravit");
                $edit->setAttribute("class", "btn btn--blue");
                $edit->href($this->link("Tutorial:edit", ["id" => $tutorial->getId()]));
                $editIcon = Html::el("i");
                $editIcon->addAttributes(array("class" => "fa fa-pencil"));
                $edit->addHtml($editIcon);

                $publish = Html::el("a");
                $publish->setAttribute("class", "btn");
                $publishIcon = Html::el("i");

                if ($tutorial->getPublished() == 0) {
                    $publish->addText("Publikovat");
                    $publish->appendAttribute("class", "btn--green");
                    $publish->href($this->link("Publish!", ["id" => $tutorial->getId(), "publish" => 1]));

                    $publishIcon->addAttributes(array("class" => "fa fa-cloud-upload"));
                } else {
                    $publish->addText("Stáhnout");
                    $publish->appendAttribute("class", "btn--orange");
                    $publish->href($this->link("Publish!", ["id" => $tutorial->getId(), "publish" => 0]));
                    $publishIcon->addAttributes(array("class" => "fa fa-cloud-download"));
                }

                $publish->addHtml($publishIcon);

                
                $el->addHtml($edit);
                $el->addHtml($publish);

                return $el;
            });

    }

    public function handlePublish($id, $publish)
    {
        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        $tutorial->setPublished($publish);
        $this->tutorialModel->flush();
    }

}