<?php
require __DIR__ . '/../vendor/autoload.php';

$refresh = (isset($argv[1]))? $argv[1] : false;
$devices = \a15lam\PhpIot\Discovery::find($refresh);
print_r($devices);