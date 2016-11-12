<?php

namespace App\FrontModule\Presenters;

use App\Components\IFilterFormFactory;
use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Nette;
use Tracy\Debugger;


class TutorialPresenter extends BasePresenter
{

    /** @var TutorialModel @inject */
    public $tutorialModel;

    /** @var  IFilterFormFactory @inject */
    public $filterFormFactory;

    protected function createComponentFilterForm()
    {
        return $this->filterFormFactory->create();
    }


    public function renderDefault()
    {
        
    }

    /**
     * Renders a detail and handles the see count
     *
     * @param $id
     */
    public function renderDetail($id)
    {
        $seenSection = $this->getSession("seen");

        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        if (isset($tutorial)) {
            if ($tutorial->getPublished() == FALSE) {
                throw new Nette\Application\BadRequestException;
            }

            $this->template->tutorial = $tutorial;

            if (!isset($seenSection->$id)) {

                $this->tutorialModel->seenIncrement($id);
                $seenSection->$id = TRUE;

                $seenSection->setExpiration("1 day", $id);
            }
        }
    }
}
