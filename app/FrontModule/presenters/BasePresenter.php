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
                "link" => $this->link("Homepage:default"),
                "active" => $this->isLinkCurrent("Homepage:default")
            ),
            array(
                "name" => "Návody",
                "link" => $this->link("Tutorial:default"),
                "active" => $this->isLinkCurrent("Tutorial:default")
            ),
            array(
                "name" => "Odkazy",
                "link" => $this->link("Page:default", array("name" => "links")),
                "active" => $this->isLinkCurrent("Page:default", array("name" => "links"))
            ),
            array(
                "name" => "O Projektu",
                "link" => $this->link("Page:default", array("name" => "about")),
                "active" => $this->isLinkCurrent("Page:default", array("name" => "about"))
            )
        );
    }

    public function beforeRender()
    {
        parent::beforeRender();

        if ($this->isAjax()) {
            $this->redrawControl("content");
        }
    }

}