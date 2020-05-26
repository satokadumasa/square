<?php 
require_once dirname(dirname(__FILE__)) . "/config/config.php";
require_once LIB_PATH . "core/ClassLoader.php";
require_once VENDOR_PATH . "autoload.php";

ini_set('error_reporting', 0);

putenv("ENVIRONMENT=development");

spl_autoload_register(['\strangerfw\core\ClassLoader', 'loadClass']);
//spl_autoload_register([function($class){require_once()}]);

require_once CONFIG_PATH . "/routes.php";
echo "LIB_PATH:".LIB_PATH."\n";
