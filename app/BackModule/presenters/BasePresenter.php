<?php

namespace App\BackModule\Presenters;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{

    public function startup()
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn() AND $this->presenter->name != "Back:Login") {
            $this->redirect("Login:login");
        }

        $this->template->menu = array(
            array(
                "name" => "Přehled",
                "icon" => "home",
                "link" => "Dashboard:default",
                "active" => "Dashboard:default"
            ),
            array(
                "name" => "Návody",
                "icon" => "file-text-o",
                "link" => "",
                "active" => "Tutorial:*",
                "sub" => array(
                    array(
                        "name" => "Seznam",
                        "link" => "Tutorial:list",
                        "active" => "Tutorial:list",
                    ),
                    array(
                        "name" => "Přidat",
                        "link" => "Tutorial:add",
                        "active" => "Tutorial:add",
                    )
                )
            ),
            array(
                "name" => "Uživatelé",
                "icon" => "user",
                "link" => "User:default",
                "active" => "User:*"
            ),
            array(
                "name" => "Stránky",
                "icon" => "file",
                "link" => "Page:default",
                "active" => "Page:*"
            ),
            array(
                "name" => "Nastavení",
                "icon" => "cogs",
                "link" => "Setting:default",
                "active" => "Setting:*"
            ),
        );
    }
}