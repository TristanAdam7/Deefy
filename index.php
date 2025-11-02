<?php
require_once "vendor/autoload.php";

use iutnc\deefy\dispatch\Dispatcher;

\iutnc\deefy\repository\DeefyRepository::setConfig('config/deefy.db.ini');

$d = new Dispatcher();
$d->run();