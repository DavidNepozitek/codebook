<?php

namespace App\BackModule\Presenters;

use App\Components\IPageFormFactory;
use Tracy\Debugger;

class PagePresenter extends BasePresenter
{

    /** @var  IPageFormFactory @inject */
    public $pageFormFactory;
    
    public function renderEdit($name)
    {
        switch ($name) {
            case "about":
                $this->template->title = "O projektu";
                break;
            case "links":
                $this->template->title = "Odkazy";
                break;
        }

        $this->template->name = $name;
    }

    protected function createComponentPageForm()
    {
        return $this->pageFormFactory->create();
    }
}