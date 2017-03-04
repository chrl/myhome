<?php
/**
 * Created by PhpStorm.
 * User: chrl
 * Date: 04/03/17
 * Time: 17:49
 */

namespace AppBundle\Action;

use AppBundle\Entity\Action;
use AppBundle\Entity\ActionHistory;
use Doctrine\Bundle\DoctrineBundle\Registry;

class Service
{

    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    private function getDoctrine()
    {
        return $this->doctrine;
    }


    public function executeVirtual(Action $action, $source, array $changeSet)
    {
        return $this->execute($action, $source, $changeSet, true);
    }

    public function execute(Action $action, $source = 'internal', array $changeSet = [], $virtual = false)
    {

        //TODO: Find and run executor here

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
}
