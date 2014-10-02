<?php
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('Omocha\\', __DIR__ . '/../src');
$loader->addPsr4('Omocha\\', __DIR__ . '/lib');
