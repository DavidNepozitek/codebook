<?php

namespace App\BackModule\Presenters;

use App\Model\RedirectHelper;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{

    /** @var  RedirectHelper @inject */
    public $redirectHelper;

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
            "link" => "",
            "active" => "Page:*",
            "sub" => array(
                array(
                    "name" => "Seznam",
                    "link" => "Page:list",
                    "active" => "Page:list",
                )
            )
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
                "link" => "User:list",
                "active" => "User:*"
            );

            $this->menu[] = array(
                "name" => "Nastavení",
                "icon" => "cogs",
                "link" => "Settings:default",
                "active" => "Settings:*"
            );
            
        }
        
        $this->template->menu = $this->menu;
    }

    public function beforeRender()
    {
        parent::beforeRender();

        if ($this->isAjax()) {
            $redirect = $this->redirectHelper->getRedirect();

            if ($redirect != NULL) {
                $this->presenter->payload->redirect = $redirect;
            }

            $this->redrawControl('title');
            $this->redrawControl('content');
            $this->redrawControl('header');
            $this->redrawControl('headerTitle');
        }
    }

}