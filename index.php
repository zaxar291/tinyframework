<?php

use bin\Implementation\WebCore\WebHost;

define("ROOT", __DIR__);

require "bin/startup/bootmgr.php";

$builder = WebHost::CreateBuilder([]);
$builder->UseStartup("Startup")
    ->Process();
