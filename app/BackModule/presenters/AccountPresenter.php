<?php

namespace App\BackModule\Presenters;

use App\Components\IPasswordChangeFormFactory;

class AccountPresenter extends BasePresenter
{
    
    /** @var  IPasswordChangeFormFactory @inject */
    public $passwordChangeFormFactory;

    protected function createComponentPasswordChangeForm()
    {
        return $this->passwordChangeFormFactory->create();
    }
}