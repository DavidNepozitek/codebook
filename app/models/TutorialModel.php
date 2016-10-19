<?php

namespace App\Model;

use App\Model\Entities\Tag;
use App\Model\Entities\Tutorial;
use Nette\Neon\Exception;
use Tracy\Debugger;

class TutorialModel extends BaseModel
{

    public $difficulties = Array("Začátečník", "Pokročilý", "Zkušený");

    /**
     * Creates a tutorial with given parameters
     *
     * @param $title
     * @param $perex
     * @param $source
     * @param $difficulty
     * @throws Exception
     */
    public function createTutorial($title, $perex, $source, $difficulty, $published, $tags)
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
        $tutorial->setPublished($published);

        foreach (json_decode($tags) as $name) {
            $tag = $this->getOne(Tag::class, array("name" => $name));

            if (!$tag) {
                $tag = new Tag();
                $tag->setName($name);
                $this->persist($tag);
            }
            $tutorial->addTag($tag);

        }

        $this->persist($tutorial);
        $this->flush();

    }

    public function editTutorial($id, $title, $perex, $source, $difficulty, $published, $tags)
    {

        $tutorial = $this->getOne(Tutorial::class, array("id" => $id));

        if (!$tutorial) {
            throw new Exception("Návod, který se snažíte upravit, neexistuje.");
        }

        $parser = new Parser();
        $content = $parser->text($source);
        $oldTags = $this->getAll(Tag::class);

        $tutorial->setTitle($title);
        $tutorial->setPerex($perex);
        $tutorial->setSource($source);
        $tutorial->setContent($content);
        $tutorial->setDifficulty($difficulty);
        $tutorial->setPublished($published);

        $tutorial->clearTags();

        foreach (json_decode($tags) as $name) {
            $tag = $this->getOne(Tag::class, array("name" => $name));

            if (!$tag) {
                $tag = new Tag();
                $tag->setName($name);
                $this->persist($tag);
            }
            $tutorial->addTag($tag);

        }

        $this->getEm()->flush();

    }
}