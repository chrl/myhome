<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vars")
 */
class Variable
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $source;

    /**
     * @ORM\Column(type="string")
     */
    private $parser;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastupdate;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $laststatus;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\VariableHistory", mappedBy="var")
     */
    public $history;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Widget", mappedBy="variable")
     */
    public $widgets;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Variable
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Variable
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return Variable
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param mixed $parser
     * @return Variable
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
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
     * @return Variable
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastupdate()
    {
        return $this->lastupdate;
    }

    /**
     * @param mixed $lastupdate
     * @return Variable
     */
    public function setLastupdate($lastupdate)
    {
        $this->lastupdate = $lastupdate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLaststatus()
    {
        return $this->laststatus;
    }

    /**
     * @param mixed $laststatus
     * @return Variable
     */
    public function setLaststatus($laststatus)
    {
        $this->laststatus = $laststatus;
        return $this;
    }

    public function __toString()
    {
        return $this->getName().' ('.$this->getValue().')';
    }
}
