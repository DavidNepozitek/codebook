<?php

namespace App\Model;

use App\Model\Entities\Image;
use App\Model\Entities\Tag;
use App\Model\Entities\Tutorial;
use Nette\Neon\Exception;

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
    public function createTutorial($title, $perex, $source, $difficulty, $published, $tags, $images)
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

        if ($tags) {
            foreach (json_decode($tags) as $name) {
                $tag = $this->getOne(Tag::class, array("name" => $name));

                if (!$tag) {
                    $tag = new Tag();
                    $tag->setName($name);
                    $this->persist($tag);
                }
                $tutorial->addTag($tag);
            }
        }

        foreach ($images as $imageId) {
            $image = $this->getOne(Image::class, array("id" => $imageId));
            $image->setTutorial($tutorial);
        }

        $this->persist($tutorial);
        $this->flush();
        
        return $tutorial;

    }

    public function editTutorial($id, $title, $perex, $source, $difficulty, $published, $tags, $images)
    {

        $tutorial = $this->getOne(Tutorial::class, array("id" => $id));
        $tutorialByName = $this->getOne(Tutorial::class, array("title" => $title));

        if (!$tutorial) {
            throw new Exception("Návod, který se snažíte upravit, neexistuje.");
        }

        if ($tutorialByName and $tutorialByName != $tutorial) {
            throw new Exception("Návod s tímto jménem již existuje.");
        }

        $parser = new Parser();
        $content = $parser->text($source);

        $tutorial->setTitle($title);
        $tutorial->setPerex($perex);
        $tutorial->setSource($source);
        $tutorial->setContent($content);
        $tutorial->setDifficulty($difficulty);
        $tutorial->setPublished($published);

        $tutorial->clearTags();

        if ($tags) {
            foreach (json_decode($tags) as $name) {
                $tag = $this->getOne(Tag::class, array("name" => $name));

                if (!$tag) {
                    $tag = new Tag();
                    $tag->setName($name);
                    $this->persist($tag);
                }
                $tutorial->addTag($tag);
            }
        }

        $tutorial->clearImages();

        foreach ($images as $imageId) {
            $image = $this->getOne(Image::class, array("id" => $imageId));
            $image->setTutorial($tutorial);
        }

        $this->flush();

    }
    
    
    public function seenIncrement($id)
    {

        $tutorial = $this->getOne(Tutorial::class, array("id" => $id));
        $newSeenCount =  $tutorial->getSeenCount() + 1;

        $tutorial->setSeenCount($newSeenCount);

        $this->flush();

    }
}