<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MeasureTemperatureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('home:measure-temp')
            ->setDescription('Measures temperature and pressure.')
            ->setHelp("This command measures temperature and pressure writes it to local variable.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exec = exec('/usr/bin/python '.__DIR__.'/../../../bin/bmp_i2c.py');

        $json = json_decode($exec, true);
		
        $varService = $this->getContainer()->get('vars');
        $varService->set('inside.temperature', $json['temp']);
        $varService->set('inside.pressure', round($json['press']));
    }
}
