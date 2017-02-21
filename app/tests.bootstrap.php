<?php

passthru('php "'.__DIR__.'/../bin/console" cache:clear --no-interaction');

require __DIR__.'/autoload.php';