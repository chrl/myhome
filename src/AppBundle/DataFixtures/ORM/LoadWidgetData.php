<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Variable;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Widget;

class LoadWidgetData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $variable = new Variable();

        $variable->setName('temperature');
        $variable->setDescription('test temperature metric');
        $variable->setSource('internal');
        $variable->setParser('Simple');
        $variable->setValue('20');

        $manager->persist($variable);

        $widget = new Widget();

        $widget->setName('temperature');
        $widget->setHeight(2);
        $widget->setType('value');
        $widget->setWidth(2);
        $widget->setX(0);
        $widget->setY(0);
        $widget->setVariable($variable);

        $manager->persist($widget);


        $manager->flush();
    }
}
