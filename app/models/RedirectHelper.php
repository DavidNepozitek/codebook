<?php

namespace App\Model;

class RedirectHelper extends BaseModel
{
    public $redirect = NULL;

    public function addRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

}