<?php
define('SITE_NAME', '書庫セラエノ');
define('PROJECT_ROOT', dirname(dirname(__FILE__)));
define('APP_PATH', PROJECT_ROOT.'/apps/');
define('SERVICE_PATH', APP_PATH.'/service/');
define('BIN_PATH', APP_PATH.'bin/');
define('CONFIG_PATH', PROJECT_ROOT.'/config/');
define('TEMP_PATH', PROJECT_ROOT.'/temp/');
define('LOG_PATH', PROJECT_ROOT.'/logs/');
define('DB_PATH', PROJECT_ROOT.'/db');
define('MIGRATION_PATH', DB_PATH.'/migrate/');
define('SCHEMA_PATH', DB_PATH.'/schema/');
define('VENDOR_PATH', PROJECT_ROOT.'/vendor/');
define('LIB_PATH', VENDOR_PATH.'strangerwork/strangerfw/src');
define('SCAFFOLD_TEMPLATE_PATH', VENDOR_PATH.'strangerwork/strangerfw//templates/');

define('CONTROLLER_PATH', APP_PATH . 'controllers/');
define('MODEL_PATH', APP_PATH . 'models/');
define('VIEW_TEMPLATE_PATH', APP_PATH . 'views/');
define('HELPER_PATH', APP_PATH . 'helpers/');

define('_CONTROLLER', '([A-Z].*)');
define('_ACTION', '([A-Z].*)');
define('SP_TAG', '##');

define('PRODUCTION', 1);
define('DEVELOPEMENT', 3);

define('ENVIRONMENTS', 'development');
define('LOG_LEVEL', DEVELOPEMENT);
define('DOMAIN_NAME', 'september-rain.com');
define('DOCUMENT_ROOT', '/');
define('BASE_URL', 'https://'.DOMAIN_NAME.DOCUMENT_ROOT);

define('SALT', 'lC0SlmdaMK');

define('COOKIE_LIFETIME', 86400);
define('COOKIE_NAME', 'AVALON');
define('USER_COOKIE_NAME_LENGTH', 64);
define('DEFAULT_FLAG_OF_AUTHENTICATION', true);

define('ADMIN_ROLE_ID', 1);
define('OPERATOR_ROLE_ID', 2);
define('USER_ROLE_ID', 3);

$CONV_STRING_LIST = [
    'ID' => '\d',
    'YEAR' => '\d{4}',
    'MONTH' => '\d{2}',
    'MDAY' => '\d{2}',
    'CONFIRM_STRING' => '\w{16}',
  ];
