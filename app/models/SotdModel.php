<?php

namespace App\Model;

use App\Model\Entities\Sotd;

class SotdModel extends BaseModel
{


    public function importSotd()
    {

        $url = "http://feeds.feedburner.com/awwwards-sites-of-the-day?format=xml";
        $xml = simplexml_load_file($url) or die("Error: Cannot create object");;


        for ($x = 0; $x <=4; $x++) {

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