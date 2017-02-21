<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="var_history")
 */
class VariableHistory
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getVar()
    {
        return $this->var;
    }

    /**
     * @param mixed $var
     * @return VariableHistory
     */
    public function setVar($var)
    {
        $this->var = $var;
        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Variable", inversedBy="history")
     */
    private $var;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $value;

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
     * @return Variable
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
        return $this->getValue();
    }
}