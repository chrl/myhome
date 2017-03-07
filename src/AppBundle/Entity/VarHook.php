<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="variable_hooks")
 */
class VarHook
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
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return VarHook
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return VarHook
     */
    public function setExecutor($executor)
    {
        $this->executor = $executor;
        return $this;
    }

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $executor;

    /**
     * @ORM\ManyToOne(inversedBy="widgets",targetEntity="AppBundle\Entity\Variable")
     */
    private $variable;

    /**
     * @ORM\ManyToOne(inversedBy="hooks",targetEntity="AppBundle\Entity\Action")
     */
    private $action;

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param mixed $arguments
     * @return VarHook
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $arguments;

    /**
     * @return Variable
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @param mixed $variable
     * @return VarHook
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;
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
     * @return VarHook
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
     * @return VarHook
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return VarHook
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }


    public function __toString()
    {
        return $this->getName();
    }
}
