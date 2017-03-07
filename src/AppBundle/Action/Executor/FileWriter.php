<?php

namespace AppBundle\Action\Executor;

use AppBundle\Entity\Action;

class FileWriter extends BaseExecutor implements ExecutorInterface
{
    public function write(Action $action)
    {

        $parameters = json_decode($action->getArguments(), true);

        foreach ($this->parameters as $k => $v) {
            $parameters[$k] = $this->parameters[$k];
        }

        return "File written successfully";
    }
}
