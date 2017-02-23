<?php
use Rocketeer\Binaries\PackageManagers\Composer;

return [

    // Task strategies
    //
    // Here you can configure in a modular way which tasks to use to
    // execute various core parts of your deployment's flow
    //////////////////////////////////////////////////////////////////////

    // Which strategy to use to check the server
    'check'        => 'Php',

    // Which strategy to use to create a new release
    'deploy'       => 'Clone',

    // Which strategy to use to test your application
    'test'         => 'Phpunit',

    // Which strategy to use to migrate your database
    'migrate'      => null,

    // Which strategy to use to install your application's dependencies
    'dependencies' => 'Polyglot',

    'composer'     => [
        'install' => function (Composer $composer, $task) {
            return $composer->install(
                [],
                [
                    '--no-interaction' => true,
                    '--no-progress'=>true,
                    '--no-suggest'=>null,
                    '--prefer-dist' => null
                ]
            );
        },
        'update'  => function (Composer $composer) {
            return $composer->update();
        },
    ],

];
