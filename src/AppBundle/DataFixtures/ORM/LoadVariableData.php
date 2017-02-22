<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Variable;

class LoadVariableData implements FixtureInterface
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


        $variable = new Variable();

        $variable->setName('humidity');
        $variable->setDescription('test humidity metric');
        $variable->setSource('internal');
        $variable->setParser('Argument');
        $variable->setValue('70');

        $manager->persist($variable);

        $variable = new Variable();

        $variable->setName('internet.upload');
        $variable->setDescription('test humidity metric');
        $variable->setSource('internal');
        $variable->setParser('Argument');
        $variable->setValue('70');

        $manager->persist($variable);

        $variable = new Variable();

        $variable->setName('internet.download');
        $variable->setDescription('test humidity metric');
        $variable->setSource('internal');
        $variable->setParser('Argument');
        $variable->setValue('70');

        $manager->persist($variable);

        $variable = new Variable();

        $variable->setName('internet.ping');
        $variable->setDescription('test humidity metric');
        $variable->setSource('internal');
        $variable->setParser('Argument');
        $variable->setValue('70');

        $manager->persist($variable);


        $manager->flush();
    }
}
