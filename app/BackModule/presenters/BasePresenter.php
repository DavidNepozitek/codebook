<?php

namespace App\BackModule\Presenters;

use App\Model\CronModel;
use App\Model\RedirectHelper;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{

    /** @var  RedirectHelper @inject */
    public $redirectHelper;

    /** @var  CronModel @inject */
    public $cronModel;

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

        $this->cronModel->doJobs();
    }

    public function beforeRender()
    {
        parent::beforeRender();

        if ($this->isAjax()) {

            $redirect = $this->redirectHelper->getRedirect();

            //TODO: Navigation on back/forward

            if (isset($redirect["redirect"])) {
                $this->presenter->payload->redirect = $redirect["redirect"];
            }

            if ($redirect["redraw"] == TRUE) {

                $this->redrawControl('title');
                $this->redrawControl('header');
                $this->redrawControl('headerTitle');
                $this->redrawControl('flashMessages');
                $this->redrawControl('content');

            } else {

                $this->redrawControl('title');
                $this->redrawControl('header');
                $this->redrawControl('headerTitle');
                $this->redrawControl('flashMessages');

            }


        }
    }

}