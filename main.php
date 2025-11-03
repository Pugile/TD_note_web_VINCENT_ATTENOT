<?php

use iutnc\deefy\dispatch\Dispatcher;

require 'vendor/autoload.php';

if (strpos(__DIR__, '/users/home/attenot12u') !== false) {
    $configPath = '/users/home/attenot12u/config/db.config.ini';
} else {
    $configPath = __DIR__ . '/config/db.config.ini';
}


\iutnc\deefy\repository\DeefyRepository::setConfig($configPath);

$action = isset($_GET['action']) ? $_GET['action'] : '';
$dispatcher = new Dispatcher($action);
$dispatcher->run();
