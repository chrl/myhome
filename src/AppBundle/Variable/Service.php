<?php

namespace AppBundle\Variable;

use AppBundle\Entity\Trigger;
use AppBundle\Entity\Variable;
use AppBundle\Entity\VariableHistory;
use AppBundle\Variable\Parser\ParserInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Exception\Exception;

class Service
{

    private $doctrine;
    private $needSync = false;
    private $syncHost;
    private $actionService;
    private $logger;

    public function __construct(
        Registry $doctrine,
        \AppBundle\Action\Service $actionService,
        $needSync,
        $syncHost,
        Logger $logger
    ) {
    
        $this->doctrine = $doctrine;
        $this->needSync = $needSync;
        $this->syncHost = $syncHost;
        $this->actionService = $actionService;
        $this->logger = $logger;
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

        $this->logger->addInfo('Set '.$var->getName().' to '.$value);

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

        // Check triggers

        $triggers = $this->
                        getDoctrine()->
                        getManager()->
                        getRepository('AppBundle:Trigger')->
                        findBy(
                            [
                                'variable'=>$var,
                                'isEnabled'=>true
                            ]
                        );

        /** @var Trigger $trigger */
        foreach ($triggers as $trigger) {
            if ($trigger->getState() == false) {
                if ($trigger->checkState()) {
                    $trigger->setState(true);


                    if ($trigger->onActivate) {
                        $tParams = json_decode($trigger->activateParams, true);
                        $tParams['variable'] = $var->getValue();
                        $this->actionService->executeReal(
                            $trigger->onActivate,
                            'trigger:activate',
                            $tParams
                        );
                    }
                }
            } else {
                if (!$trigger->checkState()) {
                    $trigger->setState(false);

                    // Deactivation hooks
                    if ($trigger->onDeactivate) {
                        $tParams = json_decode($trigger->deactivateParams, true);
                        $tParams['variable'] = $var->getValue();

                        $this->actionService->executeReal(
                            $trigger->onDeactivate,
                            'trigger:deactivate',
                            $tParams
                        );
                    }
                }
            }

            $this->getDoctrine()->getManagerForClass('AppBundle:Trigger')->persist($trigger);
            $this->getDoctrine()->getManager()->flush();
        }

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
    public function getExtremes(Variable $variable)
    {
        /**
 * @var EntityManager $em
*/
        $em = $this->getDoctrine()->getManager();

        $q = $em->createQueryBuilder();
        $res = $q->
        select('AVG(vh.value) as average')
            ->addSelect('MIN(vh.value) as minimum')
            ->addSelect('MAX(vh.value) as maximum')
            ->from('AppBundle:VariableHistory', 'vh')
            ->where('vh.time >= :date')
            ->setParameter('date', new \DateTime('-24 hour'))
            ->andWhere('vh.var = :var_id')
            ->setParameter('var_id', $variable->getId())
            ->getQuery();

        return $res->getArrayResult()[0];
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
