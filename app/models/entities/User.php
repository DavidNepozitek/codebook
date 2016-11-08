<?php

namespace App\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * @ORM\Entity
 */
class User
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
    protected $email;

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    protected $password;

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="Tutorial", mappedBy="user")
     */
    protected $tutorials;
    
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

}