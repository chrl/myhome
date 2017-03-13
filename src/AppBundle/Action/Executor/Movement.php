<?php

namespace AppBundle\Action\Executor;

use AppBundle\Entity\Action;
use AppBundle\Entity\VarHook;
use AppBundle\Entity\Variable;

class Movement extends BaseExecutor implements ExecutorInterface
{
    public function analyze(Action $action)
    {
        $varService = $this->getContainer()->get('vars');

        $params = $action->getDevice()->getParams();

        /** @var Variable $variable */
        $variable = false;

        foreach ($params as $param) {
            list($p, $v) = explode(':', $param);
            if ($p == 'variable') {
                $variable = $this->
                                getDoctrine()->
                                getManager()->
                                getRepository('AppBundle:Variable')->
                                findOneBy(['name'=>$v]);
                break;
            }
        }

        if (!$variable) {
            throw new \Exception("Sensor variable for device '".$action->getDevice()->getName()."' not found :(");
        }

        $lastValue = $varService->getLastValue($variable);
        /** @var \DateTime $lastPresence */
        $lastPresence = $lastValue['df'];
        $varService->set('room.lastPresence', time() - $lastPresence->getTimestamp());
    }
}
