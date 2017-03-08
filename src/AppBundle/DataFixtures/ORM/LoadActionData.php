<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Action;
use AppBundle\Entity\Device;
use AppBundle\Entity\VarHook;
use AppBundle\Entity\Variable;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadActionData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $variable = new Variable();

        $variable->setName('hooktest');
        $variable->setDescription('test temperature metric');
        $variable->setSource('internal');
        $variable->setParser('Simple');
        $variable->setValue('20');
        $variable->needHistory = true;
        $variable->needSync = false;

        $manager->persist($variable);

        $device = new Device();
        $device->setAlias('pi.fs');
        $device->setName('Filesystem');

        $manager->persist($device);

        $action = new Action();

        $action->setArguments(json_encode(['file'=>'/tmp/testfile.log', 'text'=>'testtext']));
        $action->setType('real');
        $action->setAlias('file.write');
        $action->setDevice($device);
        $action->setName("Write file to disk");
        $action->setExecutor('FileWriter:write');

        $manager->persist($action);

        $varHook = new VarHook();
        $varHook->setVariable($variable);
        $varHook->setAction($action);
        $varHook->setName("Test variable hook");
        $varHook->setType("decider");
        $varHook->setExecutor("FileWriter:decideTrue");
        $varHook->setArguments('{"text":"test text"}');

        $manager->persist($varHook);


        $manager->flush();
    }
}
