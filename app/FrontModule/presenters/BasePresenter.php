<?php

namespace App\FrontModule\Presenters;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{

    public function startup()
    {
        parent::startup();

        $this->template->menu = array(
            array(
                "name" => "Úvod",
                "link" => "Homepage:default",
                "active" => "Homepage:default"
            ),
            array(
                "name" => "Návody",
                "link" => "Tutorial:default",
                "active" => "Tutorial:*"
            ),
            array(
                "name" => "Odkazy",
                "link" => "Page:default",
                "active" => "Page:*"
            ),
            array(
                "name" => "O Projektu",
                "link" => "Page:default",
                "active" => "Page:*"
            )
        );
    }

}