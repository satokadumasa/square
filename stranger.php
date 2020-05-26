<?php
require_once dirname(__FILE__) . "/config/config.php";
echo "LIB_PATH:".LIB_PATH."\n";
require_once LIB_PATH . "/core/ClassLoader.php";
require_once VENDOR_PATH . "autoload.php";

ini_set('error_reporting', 1);

putenv("ENVIRONMENT=development");

spl_autoload_register(['\strangerfw\core\ClassLoader', 'loadClass']);

echo "Init Stranger\n";
echo "argv:" . print_r($argv, true) . "\n";
$stranger = new \strangerfw\core\Stranger($argv);
echo "Stranger:".print_r($stranger, true)."\n";
$stranger->execute();
