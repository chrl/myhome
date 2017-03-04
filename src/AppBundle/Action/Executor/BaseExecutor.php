<?php
/**
 * Created by PhpStorm.
 * User: chrl
 * Date: 04/03/17
 * Time: 20:57
 */

namespace AppBundle\Action\Executor;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\Container;

class BaseExecutor implements ExecutorInterface
{
    /** @var  Registry */
    public $doctrine;

    /** @var  Container */
    public $container;

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
        return $this;
    }
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }
}
