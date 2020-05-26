<?php
$route = new strangerfw\core\Route($CONV_STRING_LIST);
$route->setRoute(DOCUMENT_ROOT . 'registsdp', 'MeetingRtcSessionController', 'registsdp');
$route->setRoute(DOCUMENT_ROOT . 'remove', 'MeetingRtcSessionController', 'remove');
$route->setRoute(DOCUMENT_ROOT . 'checksdp', 'MeetingRtcSessionController', 'checksdp');

$route->setRoute(DOCUMENT_ROOT . 'login', 'UserController', 'login');
$route->setRoute(DOCUMENT_ROOT . 'auth', 'UserController', 'auth');
$route->setRoute(DOCUMENT_ROOT . 'logout', 'UserController', 'logout');
$route->setRoute(DOCUMENT_ROOT . 'confirm/CONFIRM_STRING', 'UserController', 'confirm');
// ホームページに関する記述は一番最後に
$route->setRoute(DOCUMENT_ROOT, 'DefaultController', 'index');
