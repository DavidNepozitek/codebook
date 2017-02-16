<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities\Sotd;
use App\Model\Entities\Tutorial;
use App\Model\SotdModel;
use App\Model\TutorialModel;
use Nette;
use Tracy\Debugger;


class HomepagePresenter extends BasePresenter
{

    /** @var  SotdModel @inject */
    public $sotdModel;

    /** @var  TutorialModel @inject */
    public $tutorialModel;

    public function renderDefault()
    {

        if ($this->sotdModel->checkRecency() == false) {
            $this->sotdModel->updateSotd();
        }

        $q = $this->sotdModel->findBy(Sotd::class, array(), array("pubDate" => "DESC"), 4);

        $this->template->sotds = $q;

        $q = $this->tutorialModel->findBy(Tutorial::class, array("published" => true), array("seenCount" => "DESC"), 1);

        $this->template->chosenTut = $q;

        $q = $this->tutorialModel->findBy(Tutorial::class, array("published" => "1"), array("pubDate" => "DESC"), 6);

        $this->template->tutorials = $q;

        Debugger::barDump($q);


    }

}
