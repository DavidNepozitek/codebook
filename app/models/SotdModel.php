<?php

namespace App\Model;

use App\Model\Entities\Sotd;
use Tracy\Debugger;

class SotdModel extends BaseModel
{


    /**
     * Check if there are current SOTDs in the DB
     * @return bool
     */
    public function checkRecency()
    {
        $actualDate = new \DateTime();
        $lastImportedSotd = $this->getEm()->createQuery("SELECT MAX(s.pubDate) FROM App\Model\Entities\Sotd s")->getResult();
        $lastImportedPubDate = new \DateTime($lastImportedSotd[0][1]);
        $interval = $actualDate->diff($lastImportedPubDate);


        if ($interval->d >= 1 OR $lastImportedSotd[0][1] == "") {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Imports last 5 sites of the day to the DB
     */
    public function updateSotd()
    {
        $url = "http://feeds.feedburner.com/awwwards-sites-of-the-day?format=xml";
        $xml = simplexml_load_file($url) or die("Error: Cannot create object");

        for ($x = 0; $x <= 3; $x++) {

            $pubDate = new \DateTime($xml->channel->item[$x]->pubDate);

            $item = $this->getOne(Sotd::class, array("pubDate" => $pubDate));

            if ($item) {
                break;
            }

            $item = $xml->channel->item[$x];
            $name = $item->title;
            $link = $item->link;

            $newItem = new Sotd();
            $newItem->setName($name);
            $newItem->setLink($link);
            $newItem->setPubDate($pubDate);

            $this->persist($newItem);
            $this->flush();
        }
    }

}