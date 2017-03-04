<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="actions")
 */
class Action
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $executor;

    /**
     * @ORM\Column(type="text")
     */
    private $arguments;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Device", inversedBy="actions")
     */
    private $device;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ActionHistory", mappedBy="action")
     */
    public $history;


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Action
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param mixed $device
     * @return Action
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param mixed $arguments
     * @return Action
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Action
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Action
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     * @param mixed $executor
     * @return Action
     */
    public function setExecutor($executor)
    {
        $this->executor = $executor;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
