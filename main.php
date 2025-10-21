<?php

use iutnc\deefy\dispatch\Dispatcher;

require 'vendor/autoload.php';

$action = (isset($_GET['action'])) ? $_GET['action'] : '';

$dispatcher = new Dispatcher($action);
$dispatcher->run();