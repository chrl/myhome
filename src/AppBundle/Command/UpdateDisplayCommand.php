<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDisplayCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('home:updatedisplay')
            ->setDescription('Updates LCD with var data.')
            ->setHelp("This command updates display. Can be run every second");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $lines = [];
        
        $varService = $this->getContainer()->get('vars');
        
        $lines[0] = "[Internet]   [".date('H:i').']';
        $lines[1] = "Ping:    ".$varService->get('internet.ping')->getValue()."   ms";
        $lines[2] = "Upload:  ".(round($varService->get('internet.upload')->getValue()/10000)/100) . "   mbps";
        $lines[3] = "Dload:   ".(round($varService->get('internet.download')->getValue()/10000)/100) . "  mbps";

        $exec = exec('/usr/bin/python /home/smarthome/h3p/bin/lcd_i2c.py "'.$lines[0].'" "'.$lines[1].'" "'.$lines[2].'" "'.$lines[3].'"');

        
    }
}