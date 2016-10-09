<?php

namespace App\BackModule\Presenters;

use App\Components\ITutorialFormFactory;

class TutorialPresenter extends BasePresenter
{

    /** @var  ITutorialFormFactory @inject */
    public $tutorialFormFactory;

    protected function createComponentTutorialForm()
    {
        return $this->tutorialFormFactory->create();
    }

}