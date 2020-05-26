<?php
require_once dirname(dirname(__FILE__)) . "/config/config.php";
require_once LIB_PATH . "/core/ClassLoader.php";

require_once VENDOR_PATH . "autoload.php";

ini_set('error_reporting', 1);

putenv("ENVIRONMENT=development");

spl_autoload_register([
  '\strangerfw\core\ClassLoader',
  'loadClass'
]);

require_once CONFIG_PATH . "/routes.php";

$dispatcher = new \strangerfw\core\Dispatcher($route);
$dispatcher->dispatcheController();
