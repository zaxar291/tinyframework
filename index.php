<?php

use bin\Implementation\WebHostBuilder;

define("ROOT", __DIR__);

require "bin/startup/bootmgr.php";

$builder = WebHostBuilder::BuildHost([]);
$builder->UseStartup("Startup")->Start();