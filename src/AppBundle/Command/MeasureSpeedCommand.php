<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MeasureSpeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('home:measure-speed')
            ->setDescription('Measures internet speed.')
            ->setHelp("This command measures internet speed and writes it to local variable.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Measuring internet speed');
        $exec = exec('/usr/local/bin/speedtest-cli --json');

        $json = json_decode($exec,true);

        $output->writeln($exec);

        $varService = $this->getContainer()->get('vars');
        $varService->set('internet.download', round($json['download']));
        $varService->set('internet.upload', round($json['upload']));
        $varService->set('internet.ping', $json['ping']);
    }
}