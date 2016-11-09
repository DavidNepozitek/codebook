<?php

namespace App\BackModule\Presenters;

class SettingsPresenter extends BasePresenter
{

    public function startup()
    {
        parent::startup();

        if (!$this->user->isInRole("admin")) {
            $this->redirect("Dashboard:default");
        }
    }


}