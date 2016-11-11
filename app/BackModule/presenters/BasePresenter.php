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

    public function startup()
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn() AND $this->presenter->name != "Back:Login") {

            if ($this->isAjax()) {
                $this->payload->forceRedirect = $this->link("Login:login");
            } else {
                $this->redirect("Login:login");
            }
        }

        $menu = array(
            array(
                "name" => "Přehled",
                "icon" => "home",
                "link" => $this->link("Dashboard:default"),
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
                        "link" => $this->link("Tutorial:list"),
                        "active" => $this->isLinkCurrent("Tutorial:list"),
                    ),
                    array(
                        "name" => "Přidat",
                        "link" => $this->link("Tutorial:add"),
                        "active" => $this->isLinkCurrent("Tutorial:add"),
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
                        "name" => "O projektu",
                        "link" => $this->link("Page:edit", array("name" => "about")),
                        "active" => $this->isLinkCurrent("Page:edit", array("name" => "about")),
                    ),
                    array(
                        "name" => "Odkazy",
                        "link" => $this->link("Page:edit", array("name" => "links")),
                        "active" => $this->isLinkCurrent("Page:edit", array("name" => "links")),
                    )
                )
            ),
        );

        
        if ($this->user->isInRole("admin")) {

            $menu[] = array(
                "name" => "Uživatelé",
                "icon" => "user",
                "link" => $this->link("User:list"),
                "active" => "User:*"
            );

            $menu[] = array(
                "name" => "Nastavení",
                "icon" => "cogs",
                "link" => $this->link("Settings:default"),
                "active" => "Settings:*"
            );
            
        }
        
        $this->template->menu = $menu;

        $this->cronModel->doJobs();
    }

    public function beforeRender()
    {
        parent::beforeRender();

        if ($this->isAjax()) {

            $redirect = $this->redirectHelper->getRedirect();


            if (isset($redirect["redirect"])) {
                $this->presenter->payload->redirect = $redirect["redirect"];
            }

            if ($redirect["redraw"] == TRUE) {

                $this->redrawControl('title');
                $this->redrawControl('header');
                $this->redrawControl('headerTitle');
                $this->redrawControl('flashMessages');
                $this->redrawControl('content');
                $this->redrawControl('navigation');

            } else {

                $this->redrawControl('title');
                $this->redrawControl('header');
                $this->redrawControl('headerTitle');
                $this->redrawControl('flashMessages');
                $this->redrawControl('navigation');

            }


        }
    }

}