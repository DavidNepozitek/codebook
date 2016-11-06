<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Nette;
use Tracy\Debugger;


class TutorialPresenter extends BasePresenter
{

    /** @var TutorialModel @inject */
    public $tutorialModel;

    public function renderDefault()
    {
        

    }

    public function renderDetail($id)
    {
        $seenSection = $this->getSession("seen");

        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        if (isset($tutorial)) {
            if ($tutorial->getPublished() == FALSE) {
                return;
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
