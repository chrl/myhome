<?php

namespace AppBundle\Command;

use AppBundle\Entity\Action;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCronsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('home:run-crons')
            ->setDescription('Runs cronned actions for devices')
            ->setHelp("This command takes all actions, that are marked as crons and runs it.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine');

        $crons = $doctrine->getManager()->getRepository('AppBundle:Action')->findBy(['type'=>'cron']);

        /** @var Action $cronAction */
        foreach ($crons as $cronAction) {
            $args = json_decode($cronAction->getArguments(), true);

            if (isset($args['disabled'])) {
                continue;
            }

            if (time()-$args['last'] >= $args['every']) {
                $output->writeln('Running '.$cronAction->getExecutor().' for "'.
                    $cronAction->getDevice()->getName().'" device');
                $this->
                    getContainer()->
                    get('actions')->
                    executeReal(
                        $cronAction,
                        'cron',
                        ['container'=>$this->getContainer()]
                    );
                $args['last'] = time();
                $cronAction->setArguments(json_encode($args));
                $doctrine->getManager()->persist($cronAction);
            }
        }

        $doctrine->getManager()->flush();
    }
}
