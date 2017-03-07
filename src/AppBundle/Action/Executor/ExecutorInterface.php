<?php
/**
 * Created by PhpStorm.
 * User: chrl
 * Date: 04/03/17
 * Time: 20:54
 */

namespace AppBundle\Action\Executor;

interface ExecutorInterface
{
    public function setDoctrine($doctrine);
    public function setContainer($container);
    public function setParameters($params);
}
