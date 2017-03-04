<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="devices")
 */
class Device
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
    private $alias;
    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $params;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $state = [];

    /**
     * @return mixed
     */
    public function getState()
    {
        $tmp = [];
        foreach ($this->state as $key => $value) {
            $value = explode(':', $value);
            $tmp[$value[0]] = $value[1];
        }

        return $tmp;
    }

    /**
     * @param mixed $state
     * @return Device
     */
    public function setState($state)
    {

        $tmp = [];

        foreach ($state as $key => $value) {
            if (is_numeric($key)) {
                $tmp[] = $value;
            } else {
                $tmp[] = $key.':'.$value;
            }
        }

        $this->state = $tmp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     * @return Device
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return [Action]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param mixed $actions
     * @return Device
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Action", mappedBy="device")
     */
    private $actions;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Device
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
     * @return Device
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     * @return Device
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Device
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function __toString()
    {
        return $this->getName().' ('.implode(', ', $this->getType()).')';
    }
}
