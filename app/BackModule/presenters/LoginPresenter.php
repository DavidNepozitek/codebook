<?php

namespace App\BackModule\Presenters;

use App\Components\IRegisterFormFactory;

class LoginPresenter extends BasePresenter
{
    /** @var  IRegisterFormFactory @inject */
    public $registerFormFactory;

    public function renderRegister($registered = false)
    {
        $this->template->registered = $registered;
    }

    public function actionLogout()
    {
        $this->user->logout();
        $this->redirect("Login");
    }

    protected function createComponentRegisterForm() {
        return $this->registerFormFactory->create();
    }

}