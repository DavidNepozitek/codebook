<?php

namespace App\Model;

use Nette\Object;
use Kdyby\Doctrine\EntityManager;

abstract class BaseModel extends Object
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAll($name)
    {
        return $this->em->getRepository($name)->findAll();
    }

    public function getOne($repositoryName, $parameters)
    {
        return $this->em->getRepository($repositoryName)->findOneBy($parameters);
    }

    public function findBy($repositoryName, $parameters, $order = null, $limit = null, $offset = null)
    {
        return $this->em->getRepository($repositoryName)->findBy($parameters, $order, $limit, $offset);
    }

    public function getEm()
    {
        return $this->em;
    }

    public function persist($object)
    {
        $this->em->persist($object);
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
    }

    public function clear()
    {
        $this->em->clear();
    }

    public function detach($entity)
    {
        $this->em->detach($entity);
    }
}
