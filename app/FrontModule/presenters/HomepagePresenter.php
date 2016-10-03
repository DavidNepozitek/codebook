<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities\Sotd;
use App\Model\SotdModel;
use Nette;


class HomepagePresenter extends BasePresenter
{

    /** @var  SotdModel @inject */
    public $sotdModel;

    public function renderDefault()
    {

        if ($this->sotdModel->checkRecency() == false) {
            $this->sotdModel->updateSotd();
        }

        $q = $this->sotdModel->findBy(Sotd::class, array(), array("pubDate" => "DESC"), 5);

        $this->template->sotds = $q;

    }
    
}
