<?php

namespace App\BackModule\Presenters;

use App\Components\IImageUploadFormFactory;
use App\Components\ITutorialFormFactory;
use App\Model\Entities\Image;
use App\Model\Entities\Tutorial;
use App\Model\ImageModel;
use App\Model\TutorialModel;
use Grido\DataSources\Doctrine;
use Grido\Grid;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use Tracy\Debugger;


class TutorialPresenter extends BasePresenter
{

    /** @var  ITutorialFormFactory @inject */
    public $tutorialFormFactory;

    /** @var  IImageUploadFormFactory @inject */
    public $imageUploadFormFactory;

    /** @var TutorialModel @inject */
    public $tutorialModel;

    /** @var  ImageModel */
    public $imageModel;

    private $tutorialId;

    /** @persistent array */
    public $images = Array();

    protected function createComponentTutorialForm()
    {
        return $this->tutorialFormFactory->create();
    }

    protected function createComponentImageUploadForm()
    {
        return $this->imageUploadFormFactory->create();
    }

    public function renderEdit($id)
    {
        $this->tutorialId = $id;
        $this->template->id = $id;

        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        foreach ($tutorial->getImages() as $image) {
            $imageId = $image->getId();
            $this->images[$imageId] = $imageId;
        }
    }

    public function shutdown($response)
    {
        parent::shutdown($response);

        /*if (isset($this->images)) {

            foreach ($this->images as $imageId) {
                $this->imageModel->deleteImage($imageId);
            }
        }*/

    }

    protected function createComponentGrid($name)
    {
        $grid = new Grid($this, $name);

        $grid->setTemplateFile(__DIR__ . "/../templates/Grido/bootstrap.latte");
        $grid->getTranslator()->setLang('cs');

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
                $edit->setAttribute("class", "ajax btn btn--blue");
                $edit->href($this->link("Tutorial:edit", ["id" => $tutorial->getId()]));
                $editIcon = Html::el("i");
                $editIcon->addAttributes(array("class" => "fa fa-pencil"));
                $edit->addHtml($editIcon);

                $delete = Html::el("a");
                $delete->addText("Smazat");
                $delete->setAttribute("class", "js-tutorial-delete pull-right btn btn--orange");
                $delete->setAttribute("data-title", $tutorial->getTitle());
                $delete->href($this->link("Delete!", ["id" => $tutorial->getId()]));
                $deleteIcon = Html::el("i");
                $deleteIcon->addAttributes(array("class" => "fa fa-trash"));
                $delete->addHtml($deleteIcon);

                $publish = Html::el("a");
                $publish->setAttribute("class", "btn");
                $publishIcon = Html::el("i");

                if ($tutorial->getPublished() == 0) {
                    $publish->addText("Publikovat");
                    $publish->appendAttribute("class", "ajax btn--green");
                    $publish->href($this->link("Publish!", ["id" => $tutorial->getId(), "publish" => TRUE]));

                    $publishIcon->addAttributes(array("class" => "fa fa-cloud-upload"));
                } else {
                    $publish->addText("Stáhnout");
                    $publish->appendAttribute("class", "ajax btn--orange");
                    $publish->href($this->link("Publish!", ["id" => $tutorial->getId(), "publish" => FALSE]));
                    $publishIcon->addAttributes(array("class" => "fa fa-cloud-download"));
                }

                $publish->addHtml($publishIcon);

                
                $el->addHtml($edit);
                $el->addHtml($publish);
                $el->addHtml($delete);

                return $el;
            });

    }

    public function handlePublish($id, $publish)
    {
        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        $tutorial->setPublished($publish);
        $this->tutorialModel->flush();

        if ($tutorial->getPublished() == $publish) {

            if ($publish == TRUE) {
                $this->flashMessage("Návod \"" . Strings::truncate($tutorial->getTitle(), 30) . "\" byl úspěšně publikován!", "success");
            }

            if ($publish == FALSE) {
                $this->flashMessage("Návod \"" . Strings::truncate($tutorial->getTitle(), 30) . "\" byl úspěšně stáhnut!", "success");
            }
        }


    }


    //TODO: Add confirmation
    public function handleDelete($id)
    {
        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        if ($tutorial) {
            $name = Strings::truncate($tutorial->getTitle(), 30);

            $this->tutorialModel->deleteTutorial($id);

            $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

            if (!$tutorial) {
                $this->flashMessage("Návod \"" . $name  . "\" byl úspěšně odstraněn!", "success");
            }
        }


    }

}