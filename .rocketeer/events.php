<?php

use Rocketeer\Facades\Rocketeer;


Rocketeer::before('dependencies', array(
  'composer self-update',
));

Rocketeer::before("dependencies", array(
	'cp /home/deploy/smarthome/shared/parameters.yml app/config/parameters.yml',
));

Rocketeer::after("deploy", array(
    'bin/console doctrine:schema:update --force',
));