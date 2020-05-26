<?php
require_once dirname(dirname(dirname(__FILE__))) . "/config/config.php";
require_once LIB_PATH . "/core/ClassLoader.php";
require_once VENDOR_PATH . "autoload.php";

putenv("ENVIRONMENT=development");

spl_autoload_register([
  '\strangerfw\core\ClassLoader',
  'loadClass'
]);

ini_set('error_reporting', 1);
ini_set('default_socket_timeout', 7200);

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(new HttpServer(new WsServer(new ChatService())), 8080);
$server->run();
