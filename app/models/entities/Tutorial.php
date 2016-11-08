<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * @ORM\Entity
 */
class Tutorial
{
    use MagicAccessors;

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    protected $perex;

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    protected $source;

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    protected $content;

    /**
     * @ORM\Column(type="integer")
     */
    protected $difficulty;

    /**
     * @ORM\Column(type="datetime",nullable=false)
     */
    protected $pubDate;

    /**
     * @ORM\Column(type="integer")
     */
    protected $seenCount;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     */
    protected $tags;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="tutorial",
     * cascade={"persist"})
     */
    protected $images;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $published;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tutorials")
     */
    protected $user;

    public function __construct()
    {
        $this->pubDate = new \DateTime();
        $this->seenCount = 0;
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function clearTags()
    {
        $this->tags->clear();
    }

    public function clearImages()
    {
        $this->images->clear();
    }

    public function addImage(Image $image)
    {
        $this->images->add($image);
        $image->setTutorial($this);
    }

}