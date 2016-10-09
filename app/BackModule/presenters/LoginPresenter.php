<?php

namespace App\BackModule\Presenters;

use App\Components\ILoginFormFactory;
use App\Components\IRegisterFormFactory;
use Tracy\Debugger;

class LoginPresenter extends BasePresenter
{
    /** @var  IRegisterFormFactory @inject */
    public $registerFormFactory;

    /** @var  ILoginFormFactory @inject */
    public $loginFormFactory;

    public function startup()
    {
        parent::startup();

        if ($this->getUser()->isLoggedIn() AND !$this->isLinkCurrent("Login:Logout")) {
            $this->redirect("Dashboard:default");
        }
    }

    public function actionLogout()
    {
        $this->getUser()->logout();
        $this->redirect(":Front:Homepage:default");
    }

    public function renderRegister($registered = false)
    {
        $this->template->registered = $registered;
    }

    protected function createComponentRegisterForm()
    {
        return $this->registerFormFactory->create();
    }

    protected function createComponentLoginForm()
    {
        return $this->loginFormFactory->create();
    }

}