<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * @ORM\Entity
 */
class Image
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
    protected $extension;
    
    /**
     * @ORM\ManyToOne(targetEntity="Tutorial", inversedBy="images")
     */
    protected $tutorial;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="images")
     */
    protected $user;

}