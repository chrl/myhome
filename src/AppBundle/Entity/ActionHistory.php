<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="action_history")
 */
class ActionHistory
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Action", inversedBy="history")
     */
    private $action;

    /**
     * @return boolean
     */
    public function getPerformed()
    {
        return $this->performed;
    }

    /**
     * @param mixed $performed
     * @return ActionHistory
     */
    public function setPerformed($performed)
    {
        $this->performed = $performed;
        return $this;
    }

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $performed;

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return ActionHistory
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $source = 'internal';

    /**
     * @return mixed
     */
    public function getChangeSet()
    {
        return $this->changeSet;
    }

    /**
     * @param mixed $changeSet
     * @return ActionHistory
     */
    public function setChangeSet($changeSet)
    {
        $this->changeSet = $changeSet;
        return $this;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $changeSet;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ActionHistory
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    public function __toString()
    {
        return $this->getAction()->getName();
    }

    /**
     * @return Action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return ActionHistory
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
}
