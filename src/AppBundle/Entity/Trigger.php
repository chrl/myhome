<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="triggers")
 */
class Trigger
{

    public $allowedOperators = [
        '>','<','>=','<=','==','!='
    ];

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
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param boolean $isEnabled
     * @return Trigger
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="string", length = 2)
     */
    private $sign;

    /**
     * @ORM\Column(type="string", length = 100)
     */
    private $value;


    public function getExpression()
    {
        return $this->sign.''.$this->value;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $state = false;

    /**
     * @var Variable
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Variable", inversedBy="triggers")
     */
    public $variable;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Action", inversedBy="activateTriggers")
     */
    public $onActivate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Action", inversedBy="deactivateTriggers")
     */
    public $onDeactivate;

    /** @ORM\Column(type="text") */
    public $activateParams;
    /** @ORM\Column(type="text") */
    public $deactivateParams;

    /**
     * @param $state boolean
     * @return Trigger
     */
    public function setState($state)
    {
        $this->state = !!$state;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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
     * @return Trigger
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
     * @param string $name
     * @return Trigger
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function checkState()
    {
        $value = $this->getVariable()->getValue();

        if (!is_numeric($this->getValue())) {
            return false;
        }
        if (!in_array($this->getSign(), $this->allowedOperators)) {
            return false;
        }

        return (bool) eval('return '.$value.' '.$this->getExpression().';');
    }

    /**
     * @return Variable
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @param mixed $variable
     * @return Trigger
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;
        return $this;
    }

    public function __toString()
    {
        return $this->getName(). ($this->getState() ? ' (on)':' (off)');
    }
}
