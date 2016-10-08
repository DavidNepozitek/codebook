<?php

namespace App\BackModule\Presenters;

use App\Components\ILoginFormFactory;
use App\Components\IRegisterFormFactory;

class LoginPresenter extends BasePresenter
{
    /** @var  IRegisterFormFactory @inject */
    public $registerFormFactory;

    /** @var  ILoginFormFactory @inject */
    public $loginFormFactory;

    public function startup()
    {
        parent::startup();

        if ($this->getUser()->isLoggedIn() AND $this->presenter->name != "Back:Login:Login" AND $this->presenter->name != "Back:Login:Register") {
            //$this->redirect("Dashboard:default");
        }
    }

    public function renderRegister($registered = false)
    {
        $this->template->registered = $registered;
    }

    public function actionLogout()
    {
        $this->getUser()->logout();
        $this->redirect(":Front:Homepage:default");
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