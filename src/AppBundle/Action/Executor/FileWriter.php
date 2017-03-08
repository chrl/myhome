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

        file_put_contents($parameters['file'], $parameters['text']);

        return "File written successfully";
    }

    public function decideTrue()
    {
        return true;
    }

    public function decideFalse()
    {
        return false;
    }
}
