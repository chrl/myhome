<?php

namespace AppBundle\Command;

use AppBundle\Entity\Action;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunActionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('home:run')
            ->setDescription('Runs action')
            ->setHelp("This runs one action separately, passes it given arguments")
            ->addArgument('action', InputArgument::REQUIRED, 'Who action do you want to execute?')
            ->addOption(
                'arg',
                'a',
                InputOption::VALUE_OPTIONAL,
                'Arguments to the command'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $actionAlias = $input->getArgument('action');

        $doctrine = $this->getContainer()->get('doctrine');

        $action = $doctrine->getManager()->getRepository('AppBundle:Action')->findOneBy(['alias'=>$actionAlias]);

        if (!$action) {
            $output->writeln('No action "'.$actionAlias."' found :(");
            return;
        }

        $args = json_decode($action->getArguments(), true);

        $output->writeln('Running '.$action->getExecutor().' for "'.
            $action->getDevice()->getName().'" device');

        $additionalParams = [];

        if ($input->getOption('arg')) {
            $additionalParams = json_decode($input->getOption('arg'), true);
        }

        $additionalParams['container'] = $this->getContainer();

        $this->
            getContainer()->
            get('actions')->
            executeReal(
                $action,
                'onetimecli',
                $additionalParams
            );
        $args['last'] = time();
        $action->setArguments(json_encode($args));
        $doctrine->getManager()->persist($action);

        $doctrine->getManager()->flush();
    }
}
