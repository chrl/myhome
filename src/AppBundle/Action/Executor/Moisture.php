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

		$params = $action->getDevice()->getParams();


	}
}
