<?php

namespace App\Model;

use Kdyby\Doctrine\EntityManager;
use Nette\Utils\DateTime;

class CronModel extends BaseModel
{

    CONST FILE = "temp/cron.json";

    /** @var  ImageModel */
    private $imageModel;

    public $jobs = array(
        "removeImages" => "+1 day"
    );

    private $currentTime;

    public function __construct(EntityManager $em, ImageModel $imageModel)
    {
        parent::__construct($em);

        $this->imageModel = $imageModel;
        $this->currentTime = new DateTime();

    }

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