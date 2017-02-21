<?php

namespace App\Model\Listeners;

use App\Model\Log\contentChangeLog;
use App\Model\PageModel;
use Kdyby\Events\Subscriber;
use Nette\Security\Identity;

class PageListener implements Subscriber
{

    private $contentChangeLog;

    public function __construct(contentChangeLog $contentChangeLog)
    {
        $this->contentChangeLog = $contentChangeLog;
    }

    public function getSubscribedEvents()
    {
        return [PageModel::class . '::onSuccess' => "onSuccess"];
    }

    public function onSuccess($name, Identity $identity)
    {
        $this->contentChangeLog->logPageChange($name, $identity->data["email"]);
    }
}
