<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Nette;


class TutorialPresenter extends BasePresenter
{

    /** @var TutorialModel @inject */
    public $tutorialModel;

    public function renderDefault()
    {
        

    }

    public function renderDetail($id)
    {
        $tutorial = $this->tutorialModel->getOne(Tutorial::class, array("id" => $id));

        $this->template->content = $tutorial->getContent();
    }
}
