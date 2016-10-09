<?php

namespace App\Model;

use App\Model\Entities\Tutorial;
use Nette\Neon\Exception;

class TutorialModel extends BaseModel
{

    CONST Difficulties = Array("Začátečník", "Pokročilý", "Zkušený");

    /**
     * Creates a tutorial with given parameters
     *
     * @param $title
     * @param $perex
     * @param $source
     * @param $difficulty
     * @throws Exception
     */
    public function createTutorial($title, $perex, $source, $difficulty)
    {

        $tutorial = $this->getOne(Tutorial::class, array("title" => $title));

        if ($tutorial) {
            throw new Exception("Návod s tímto jménem již existuje");
        }

        $parser = new Parser();
        $content = $parser->text($source);

        $tutorial = new Tutorial();

        $tutorial->setTitle($title);
        $tutorial->setPerex($perex);
        $tutorial->setSource($source);
        $tutorial->setContent($content);
        $tutorial->setDifficulty($difficulty);

        $this->getEm()->persist($tutorial);
        $this->flush();

    }
}