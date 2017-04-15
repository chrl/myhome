<?php

namespace AppBundle\Action\Executor;

use AppBundle\Entity\Action;
use AppBundle\Entity\VarHook;
use AppBundle\Entity\Variable;

class Moisture extends BaseExecutor implements ExecutorInterface
{

    public function getData(Action $action)
    {
        $varService = $this->getContainer()->get('vars');

        $fp = file_get_contents("http://192.168.0.221/data.json");

        if (!$fp) {
            return 'Couldn\'t connect to sensor';
        }

        $this->getContainer()->get('logger')->addInfo('Got sensor data: '.$fp);
        $fp = json_decode($fp, true);

        $varService->set('outside.temperature', $fp['data']['temperature']);
        $varService->set('moisture.raw', $fp['data']['rowmoisture']);
        $varService->set('moisture.combined', $fp['data']['moisture']);

        return 'Info recorded successfully';
    }
}
