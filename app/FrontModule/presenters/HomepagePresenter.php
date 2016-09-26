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
        
        $this->sotdModel->importSotd();

        $q = $this->sotdModel->getEm()->createQueryBuilder()
            ->select("s")
            ->from(Sotd::class, "s")
            ->orderBy("s.pubDate", "DESC")
            ->setMaxResults(5)
            ->getQuery();

        $this->template->sotds = $q->getResult();

    }
    
}
