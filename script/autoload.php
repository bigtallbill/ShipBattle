<?php

// composer autoloader
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

// other classes
use MarketMeSuite\Phranken\Spl\SplClassLoader;

// register local autoloader
$loader = new SplClassLoader('Bigtallbill', dirname(dirname(__FILE__)) . '/src');
$loader->register();
