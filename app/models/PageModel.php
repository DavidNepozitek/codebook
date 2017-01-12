<?php

namespace App\Model;

use App\Model\Entities\Page;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;
use Nette\Security\User;
use Tracy\Debugger;

class PageModel extends BaseModel
{

    /** @var array|Event */
    public $onSuccess = [];

    /** @var  User */
    private $user;

    public function __construct(EntityManager $em, User $user)
    {
        parent::__construct($em);

        $this->user = $user;
    }

    /**
     * Updates or creates a page
     *
     * @param $name
     * @param $title
     * @param $source
     */
    public function editPage($name, $title, $source)
    {
        $page = $this->getOne(Page::class, array("name" => $name));
        $parser = new Parser();
        $content = $parser->text($source);

        if ($page) {
            $page->setTitle($title);
            $page->setSource($source);
            $page->setContent($content);
        } else {
            $page = new Page();

            $page->setName($name);
            $page->setTitle($title);
            $page->setSource($source);
            $page->setContent($content);

            $this->persist($page);
        }

        $this->flush();

        Debugger::barDump($this->user->getIdentity());
    }
}