<?php

namespace App\BackModule\Presenters;

use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;

class DashboardPresenter extends BasePresenter
{

    /** @var  TutorialModel @inject */
    public $tutorialModel;

    public function beforeRender()
    {
        parent::beforeRender();

        $topTuts = $this->tutorialModel->findBy(Tutorial::class, array(), array("seenCount" => "DESC"), 5);

        $this->template->topTuts = $topTuts;
    }
}