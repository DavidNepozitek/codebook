<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * @ORM\Entity
 */
class Page
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
    protected $name;

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    protected $source;

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    protected $content;

}