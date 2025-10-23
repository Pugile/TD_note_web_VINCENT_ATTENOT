<?php

use iutnc\deefy\dispatch\Dispatcher;

require 'vendor/autoload.php';

\iutnc\deefy\repository\DeefyRepository::setConfig(__DIR__ . '/config/db.config.ini');

$action = (isset($_GET['action'])) ? $_GET['action'] : '';

$dispatcher = new Dispatcher($action);
$dispatcher->run();