<?php

namespace App\Model;

use Kdyby\Doctrine\EntityManager;
use Nette\Utils\DateTime;

class CronModel extends BaseModel
{

    CONST FILE = "temp/cron.json";

    /** @var  ImageModel */
    private $imageModel;

    /** @var ConfigModel  */
    private $configModel;

    private $jobs = [];

    private $currentTime;


    public function __construct(EntityManager $em, ImageModel $imageModel, ConfigModel $configModel)
    {
        parent::__construct($em);

        $this->imageModel = $imageModel;
        $this->currentTime = new DateTime();
        $this->configModel = $configModel;

        $this->jobs = $this->configModel->getSection("crons");

    }

    /**
     * Updates the cron file and does planned jobs
     */
    public function doJobs()
    {

        $this->updateCronFile();

        if (file_exists($this::FILE)) {

            $content = file_get_contents($this::FILE);
            $content = json_decode($content, TRUE);

            foreach ($content as $job => $time) {
                $time = new DateTime($time);

                if ($time < $this->currentTime) {

                    switch ($job) {
                        case "removeImages":
                            $this->imageModel->purgeImages();
                            break;
                    }

                    $timeout = new \DateTime($this->jobs[$job]);
                    $content[$job] = $timeout->format("Y-m-d\\TH:i:sP");
                }
            }

            $file = fopen($this::FILE, "w");
            fwrite($file, json_encode($content));
            fclose($file);
        }
    }

    /**
     * Creates the cron file if doesn't exist, updates the cron file according to the $jobs array
     */
    public function updateCronFile()
    {
        if (file_exists($this::FILE)) {
            $content = file_get_contents($this::FILE);
            $content = json_decode($content, TRUE);

            foreach ($content as $job => $time) {

                if (isset($this->jobs[$job])) {
                    continue;
                } else {
                    unset($content[$job]);
                }
            }

        } else {
            $content = array();
        }

        foreach ($this->jobs as $job => $time) {

            if (isset($content[$job])) {
                continue;
            } else {
                $content[$job] = $this->currentTime->format("Y-m-d\\TH:i:sP");
            }
        }

        $file = fopen($this::FILE, "w");
        fwrite($file, json_encode($content));
        fclose($file);
    }
}