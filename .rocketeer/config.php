<?php

use Rocketeer\Services\Connections\ConnectionsHandler;

return [

    'application_name' => 'smarthome',

    'plugins'          => [
    ],
    'logs'             => function (ConnectionsHandler $connections) {
        return sprintf('%s-%s-%s.log', $connections->getConnection(), $connections->getStage(), date('Ymd'));
    },
    'default'          => ['production'],
    'connections'      => [
        'production' => [
            'host'      => 'koelnhome.ru',
            'username'  => 'deploy',
            'password'  => getenv('DEPLOY_PWD','nopassword'),
            'key'       => false,
            'keyphrase' => null,
            'agent'     => '',
            'db_role'   => true,
        ],
		'localpi' => [
			'host'      => '192.168.0.100',
			'username'  => 'pi',
			'password'  => null,
			'key'       => null,
			'keyphrase' => null,
			'agent'     => '',
			'db_role'   => true,
		],
    ],
    'use_roles'        => true,
    'on'               => [
		'production' => array(
				'root_directory' => '/home/deploy/',
		),
    ],

];
