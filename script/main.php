#!/usr/bin/php
<?php

require_once dirname(__FILE__) . '/autoload.php';

use MarketMeSuite\Phranken\Commandline\CommandPrompt;
use MarketMeSuite\Phranken\Commandline\SimpleLog;

use Bigtallbill\ShipBattle\V1\Main;

// build interaction classes
$cmdPrompt = new CommandPrompt();
$sl        = new SimpleLog();

// create a new game application class
$main = new Main($cmdPrompt, $sl);

// start game
$main->showMainMenu();
