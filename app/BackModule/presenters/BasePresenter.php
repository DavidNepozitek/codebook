<?php

namespace App\BackModule\Presenters;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{

    protected $menu = array(
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
            "name" => "Stránky",
            "icon" => "file",
            "link" => "Page:default",
            "active" => "Page:*"
        ),

    );

    public function startup()
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn() AND $this->presenter->name != "Back:Login") {
            $this->redirect("Login:login");
        }

        
        if ($this->user->isInRole("admin")) {

            $this->menu[] = array(
                "name" => "Uživatelé",
                "icon" => "user",
                "link" => "User:default",
                "active" => "User:*"
            );

            $this->menu[] = array(
                "name" => "Nastavení",
                "icon" => "cogs",
                "link" => "Setting:default",
                "active" => "Setting:*"
            );
            
        }
        
        $this->template->menu = $this->menu;
    }
}