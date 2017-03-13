<?php

namespace AppBundle\Variable;

use AppBundle\Action\Executor\ExecutorInterface;
use AppBundle\Entity\Variable;
use AppBundle\Entity\VariableHistory;
use AppBundle\Variable\Parser\ParserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class Service
{

    private $doctrine;
    private $needSync = false;
    private $syncHost;
    private $actionService;

    public function __construct(Registry $doctrine, \AppBundle\Action\Service $actionService, $needSync, $syncHost)
    {
        $this->doctrine = $doctrine;
        $this->needSync = $needSync;
        $this->syncHost = $syncHost;
        $this->actionService = $actionService;
    }

    private function getDoctrine()
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

    public function set($varName, $value)
    {
        $vars = $this->getDoctrine()->getRepository('AppBundle:Variable');

        /** @var Variable $var */
        $var = $vars->findOneBy(['name'=>$varName]);
        if (!$var) {
            throw new Exception('Variable '.$varName.' not found');
        }

        $parser = 'AppBundle\Variable\Parser\\'.ucfirst($var->getParser());

        if (!class_exists($parser)) {
            throw new Exception('Unknown parser: '.$parser);
        }

        /** @var ParserInterface $parser */
        $parser = new $parser();

        $value = $parser->parse($value);

        if (!$value) {
            return false;
        }

        if ($this->needSync) {
            if ($var->needSync) {
                @file_get_contents($this->syncHost.'set/'.$varName.'?value='.$value);
            }
        }

        $var->setValue($value);
        $var->setLaststatus(200);
        $var->setLastupdate(new \DateTime());

        $this->getDoctrine()->getManagerForClass('AppBundle:Variable')->persist($var);

        if ($var->needHistory) {
            $state = new VariableHistory();
            $state->setVar($var);
            $state->setTime(new \DateTime());
            $state->setValue($value);

            $this->getDoctrine()->getManagerForClass('AppBundle:VariableHistory')->persist($state);
            $this->getDoctrine()->getManagerForClass('AppBundle:VariableHistory')->flush();
        }

        $this->getDoctrine()->getManagerForClass('AppBundle:Variable')->flush();


        return $value;
    }

    /**
     * @param Variable $variable
     * @return array
     */
    public function getDayHistory(Variable $variable)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $q = $em->createQueryBuilder();
        $res = $q->
            select('AVG(vh.value) as av')->
            addSelect('DATE_FORMAT(vh.time,\'%Y-%m-%d %H:00\') as df')->
            from('AppBundle:VariableHistory', 'vh')->
            where('vh.time >= :date')->
            setParameter('date', new \DateTime('-48 hour'))->
            andWhere('vh.var = :var_id')->
            setParameter('var_id', $variable->getId())->
            groupBy('df')->
            orderBy('df', 'asc')->
            getQuery();

        return $res->getArrayResult();
    }

    /**
     * @param Variable $variable
     * @return array
     */
    public function getLastValue(Variable $variable)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $q = $em->createQueryBuilder();
        $res = $q->
        select('vh.value as av')->
        addSelect('vh.time as df')->
        from('AppBundle:VariableHistory', 'vh')->
        where('vh.var = :var_id')->
        setParameter('var_id', $variable->getId())->
        orderBy('df', 'desc')->
        setMaxResults(1)->
        getQuery();

        return $res->getSingleResult();
    }
}
