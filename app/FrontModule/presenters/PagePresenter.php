<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities\Page;
use App\Model\PageModel;
use Nette;


class PagePresenter extends BasePresenter
{


    /** @var  PageModel @inject */
    public $pageModel;

    public function renderDefault($name)
    {
        $page = $this->pageModel->getOne(Page::class, array("name" => $name));
        
        $this->template->page = $page;
    }
}
