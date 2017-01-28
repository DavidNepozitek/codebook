<?php

namespace App\BackModule\Presenters;

use App\Model\Entities\Tutorial;
use App\Model\TutorialModel;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Kdyby\Console\StringOutput;
use Kdyby\Doctrine\Console\SchemaUpdateCommand;
use Kdyby\Doctrine\Tools\CacheCleaner;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;

class DashboardPresenter extends BasePresenter
{

    /** @var  TutorialModel @inject */
    public $tutorialModel;

    /** @var  SchemaUpdateCommand @inject */
    public $schemaUpdate;

    /** @var  CacheCleaner @inject */
    public $cacheCleaner;

    public function beforeRender()
    {
        parent::beforeRender();

        $topTuts = $this->tutorialModel->findBy(Tutorial::class, array(), array("seenCount" => "DESC"), 5);

        $this->template->topTuts = $topTuts;
    }

    public function handleSchemaUpdate()
    {
        $ips = ["193.165.123.146", "::1", "127.0.0.1"];
        if (!in_array($_SERVER['REMOTE_ADDR'], $ips)) {
            echo "Invalid IP";
            $this->terminate();
        }
        $input = new ArrayInput(array('--force' => true));
        $output = new StringOutput();
        $this->schemaUpdate->cacheCleaner = $this->cacheCleaner;
        $this->schemaUpdate->setHelperSet(new HelperSet(['em' => new EntityManagerHelper($this->tutorialModel->getEm())]));
        $this->schemaUpdate->run($input, $output);
        $this->terminate();
    }
}