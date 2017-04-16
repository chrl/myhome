<?php

namespace AppBundle\Action\Executor;

use AppBundle\Entity\Action;
use AppBundle\Entity\VarHook;
use AppBundle\Entity\Variable;

class DayReport extends BaseExecutor implements ExecutorInterface
{
    public function printData(Action $action)
    {
        $varService = $this->getContainer()->get('vars');

        $this->getContainer()->get('logger')->addInfo('Getting report data');

        $title = [
        'Утренний отчет'."\n",
        (new \DateTime())->format('Y-m-d H:i:s')
        ];

        $temp = $varService->get('inside.temperature');
        $tempInside = $varService->getExtremes($temp);

        $temp = $varService->get('outside.temperature');
        $tempOutside = $varService->getExtremes($temp);

        $temp = $varService->get('inside.pressure');
        $pressureInside = $varService->getExtremes($temp);

        $thermal = $this->getContainer()->get('thermal');

        $thermal->
        hhr()
            ->writeText($title[0])
            ->writeText($title[1])
            ->hhr()
            ->writeText('Themperature')
            ->hhr();
    }
}
