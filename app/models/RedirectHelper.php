<?php

namespace App\Model;

/**
 * This class handles AJAX redirect request
 *
 * Class RedirectHelper
 * @package App\Model
 */
class RedirectHelper extends BaseModel
{
    public $redirect = Array("redirect" => NULL, "redraw" => TRUE);

    public function setRedirect($redirect = NULL, $redraw = NULL)
    {
        $this->redirect["redirect"] = $redirect;
        $this->redirect["redraw"] = $redraw;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

}