<?php

namespace App\Model;

use App\Model\Entities\Attachment;
use App\Model\Entities\Tag;
use App\Model\Entities\Tutorial;
use App\Model\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Event;
use Nette\Neon\Exception;
use Tracy\Debugger;

class TutorialModel extends BaseModel
{

    /** @var  AttachmentModel */
    private $attachmentModel;

    /** @var array|Event  */
    public $onCreateSuccess = [];

    /** @var array|Event  */
    public $onCreateError = [];

    /** @var array|Event  */
    public $onEditSuccess = [];

    public $difficulties = Array("Začátečník", "Pokročilý", "Zkušený");

    public function __construct(EntityManager $em, AttachmentModel $attachmentModel)
    {
        parent::__construct($em);

        $this->attachmentModel = $attachmentModel;
    }


    /**
     * Creates a tutorial with given parameters
     *
     * @param $title
     * @param $perex
     * @param $source
     * @param $difficulty
     * @param $published
     * @param $tags
     * @param $attachments
     * @param $userId
     * @return Tutorial|mixed|null|object
     * @throws Exception
     */
    public function createTutorial(
        $title, $perex, $source, $difficulty,
        $published, $tags, $attachments, $userId
    ) {

        $tutorial = $this->getOne(Tutorial::class, array("title" => $title));

        if ($tutorial) {
            throw new Exception("Návod s tímto jménem již existuje");
        }

        $user = $this->getOne(User::class, array("id" => $userId));

        $parser = new Parser();
        $content = $parser->text($source);

        $tutorial = new Tutorial();

        $tutorial->setUser($user);
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

        foreach (json_decode($attachments) as $attachmentId) {
            $attachment = $this->getOne(
                Attachment::class, array("id" => $attachmentId)
            );
            $attachment->setTutorial($tutorial);
        }

        $this->persist($tutorial);
        $this->flush();



        return $tutorial;

    }

    /**
     * Edits a tutorial
     *
     * @param $id
     * @param $title
     * @param $perex
     * @param $source
     * @param $difficulty
     * @param $published
     * @param $tags
     * @param $attachments
     * @throws Exception
     */
    public function editTutorial(
        $id, $title, $perex, $source, $difficulty,
        $published, $tags, $attachments
    ) {

        $tutorial = $this->getOne(Tutorial::class, array("id" => $id));
        $tutorialByName = $this->getOne(Tutorial::class, array("title" => $title));

        if (!$tutorial) {
            throw new Exception("Návod, který se snažíte upravit, neexistuje.");
        }

        if ($tutorialByName AND $tutorialByName != $tutorial) {
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

        $tutorial->clearAttachments();

        foreach (json_decode($attachments) as $attachmentId) {
            $attachment = $this->getOne(
                Attachment::class, array("id" => $attachmentId)
            );
            $attachment->setTutorial($tutorial);
        }

        $this->flush();

    }


    /**
     * Increments the seen count of the tutorial with given ID
     *
     * @param $id
     */
    public function seenIncrement($id)
    {

        $tutorial = $this->getOne(Tutorial::class, array("id" => $id));
        $newSeenCount = $tutorial->getSeenCount() + 1;

        $tutorial->setSeenCount($newSeenCount);

        $this->flush();

    }

    /**
     * Removes a tutorial and all attachments assigned to it
     *
     * @param $id
     */
    public function deleteTutorial($id)
    {
        $tutorial = $this->getOne(Tutorial::class, array("id" => $id));
        $attachmentIds = array();

        if ($tutorial) {

            foreach ($tutorial->getAttachments() as $attachment) {
                $attachmentIds[] = $attachment->getId();
            }

            $this->attachmentModel->deleteAttachment($attachmentIds);

            $this->remove($tutorial);
            $this->flush();
        }

        foreach ($this->getAll(Tag::class) as $tag) {
            $tutorials = $tag->getTutorials();

            if (isset($tutorials)) {
                if ($tutorials->isEmpty()) {
                    $this->remove($tag);
                }
            }
        }

        $this->flush();
    }
}