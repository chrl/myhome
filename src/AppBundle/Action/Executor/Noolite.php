<?php

namespace AppBundle\Action\Executor;

use AppBundle\Entity\Action;

class Noolite extends BaseExecutor implements ExecutorInterface
{
    public function toggle(Action $action)
    {
        $device = $action->getDevice();

        $deviceParams = $device->getParams();
        $ch = 0;
        foreach ($deviceParams as $deviceParam) {
            list($param, $value) = explode(':', $deviceParam);
            if ($param == 'channel') {
                $ch = $value;
                break;
            }
        }

        if (!$ch) {
            throw new \Exception("Found device with undefined param!");
        }

        $actionArgs = json_decode($action->getArguments(), true);
        exec('/usr/local/bin/noolite -api -'.$actionArgs['state'].'_ch '.$ch);

        return "Switched device ".$actionArgs['state'].', channel '.$ch;
    }
}
