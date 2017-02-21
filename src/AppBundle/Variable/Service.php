<?php

namespace AppBundle\Variable;

use AppBundle\Entity\Variable;
use AppBundle\Entity\VariableHistory;
use AppBundle\Variable\Parser\ParserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Config\Definition\Exception\Exception;

class Service
{

    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getDoctrine()
    {
        return $this->doctrine;
    }

    public function get($varName)
    {
        $vars = $this->getDoctrine()->getRepository('AppBundle:Variable');

        /** @var Variable $var */
        $var = $vars->findOneBy(['name'=>$varName]);
        if (!$var) {
            throw new Exception('Variable '.$varName.' not found');
        }
        return $var;
    }

    public function set($varName, $value) {
        $vars = $this->getDoctrine()->getRepository('AppBundle:Variable');

        /** @var Variable $var */
        $var = $vars->findOneBy(['name'=>$varName]);
        if (!$var) {
            throw new Exception('Variable '.$varName.' not found');
        }

        $parser = 'AppBundle\Variable\Parser\\'.ucfirst($var->getParser());

        if (!class_exists($parser)) {
            throw new Exception('Unknown parser: ' . $parser);
        }

        /** @var ParserInterface $parser */
        $parser = new $parser();

        $value = $parser->parse($value);

        if (!$value) {
            return false;
        }


        $var->setValue($value);
        $var->setLaststatus(200);
        $var->setLastupdate(new \DateTime());

        $this->getDoctrine()->getManagerForClass('AppBundle:Variable')->persist($var);

        $state = new VariableHistory();
        $state->setVar($var);
        $state->setTime(new \DateTime());
        $state->setValue($value);

        $this->getDoctrine()->getManagerForClass('AppBundle:VariableHistory')->persist($state);

        $this->getDoctrine()->getManagerForClass('AppBundle:Variable')->flush();
        $this->getDoctrine()->getManagerForClass('AppBundle:VariableHistory')->flush();

        return $value;
    }
}