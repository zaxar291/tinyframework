<?php

use bin\startup\BootConfiguration;
use bin\startup\HostLoader;

require "BootConfiguration.php";
require "FileType.php";
require "FileEntityType.php";
require "FileTypeParser.php";
require "HostLoader.php";

if (!defined("ROOT")) {
    define("ROOT", dirname(__DIR__));
}

$configuration = new BootConfiguration(
    ROOT,
    ROOT . "/",
    ROOT,
    ROOT,
    ROOT . "/compress.out/",
    "7.4"
);

require ROOT . "/bin/Data/Smarty.class.php";
$l = new HostLoader($configuration);
$l->InitApplication();