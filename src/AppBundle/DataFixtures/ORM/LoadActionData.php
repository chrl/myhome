<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Action;
use AppBundle\Entity\Device;
use AppBundle\Entity\Trigger;
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

        $trigger = new Trigger();
        $trigger->setVariable($variable);
        $trigger->setIsEnabled(true);
        $trigger->setSign('>');
        $trigger->setState(false);
        $trigger->setValue(20);
        $trigger->setName("Test variable hook");
        $trigger->onActivate = $action;
        $trigger->activateParams = '{"text":"onActivate text"}';

        $trigger->onDeactivate = $action;
        $trigger->deactivateParams = '{"text":"onDeactivate text"}';


        $manager->persist($trigger);


        $manager->flush();
    }
}
