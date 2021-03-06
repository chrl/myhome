<?php
/**
 * Created by PhpStorm.
 * User: chrl
 * Date: 04/03/17
 * Time: 17:49
 */

namespace AppBundle\Action;

use AppBundle\Action\Executor\ExecutorInterface;
use AppBundle\Entity\Action;
use AppBundle\Entity\ActionHistory;
use Doctrine\Bundle\DoctrineBundle\Registry;

class Service
{

    private $doctrine;
    private $container;

    public function __construct(Registry $doctrine, $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }

    private function getDoctrine()
    {
        return $this->doctrine;
    }


    public function executeVirtual(Action $action, $source, array $changeSet)
    {
        return $this->execute($action, $source, $changeSet, true);
    }

    /**
     * @param string $source
     * @return Service
     */
    public function executeReal(Action $action, $source, $arguments)
    {
        return $this->execute($action, $source, $arguments, false);
    }

    public function execute(Action $action, $source = 'internal', array $changeSet = [], $virtual = false)
    {

        //TODO: Find and run executor here

        if (!$virtual) {
            list($executor, $method) = explode(':', $action->getExecutor());

            $executor = 'AppBundle\Action\Executor\\'.ucfirst($executor);

            if (!class_exists($executor)) {
                throw new \Exception('Unknown executor: '.$executor);
            }

            /** @var ExecutorInterface $executor */
            $executor = new $executor();
            $executor->setDoctrine($this->getDoctrine());

            $executor->setContainer($this->container);
            unset($changeSet['container']);


            if (!method_exists($executor, $method)) {
                throw new \Exception('Unknown executor method: '.$action->getExecutor().'()');
            }

            $executor->setParameters($changeSet);

            try {
                $result = $executor->{$method}($action);
            } catch (\Exception $exception) {
                $result = $exception->getMessage();
            }
            $changeSet['result'] = $result;
        }

        $state = new ActionHistory();
        $state->setAction($action);
        $state->setTime(new \DateTime());
        $state->setSource($source);
        $state->setPerformed(!$virtual);
        $state->setChangeSet(json_encode($changeSet));

        $device = $action->getDevice()->setState($changeSet);

        $this->getDoctrine()->getManagerForClass('AppBundle:ActionHistory')->persist($state);
        $this->getDoctrine()->getManagerForClass('AppBundle:Device')->persist($device);

        $this->getDoctrine()->getManager()->flush();

        return $this;
    }

    public function runAction($actionName, array $parameters)
    {
        $action = $this->doctrine->getRepository('AppBundle:Action')->findOneBy(['name'=>$actionName]);
        if (!$action) {
            throw new \Exception("Action ".$actionName." not found");
        }


        if (isset($parameter['container'])) {
            $args['container'] = $parameter['container'];
        }
        $this->executeReal($action, "internalcall", $parameters);
    }
}
