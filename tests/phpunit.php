<?php

$loader = require realpath(__DIR__ . '/../') . '/vendor/autoload.php';
$loader->add('OU\\', realpath(__DIR__ . '/src/'));
date_default_timezone_set('UTC');