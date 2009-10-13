<?php

if (isset($_GET['debug'])) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('PS')) define('PS', PATH_SEPARATOR);

define('PUBLIC_ROOT', dirname($_SERVER['SCRIPT_FILENAME']));

define('PATH_PREFIX', preg_replace('/\/'.basename(PUBLIC_ROOT).'\/'.basename($_SERVER['SCRIPT_NAME']).'/', '', $_SERVER['SCRIPT_NAME']));

define('IMAGES_DIRECTORY', 'images');
define('JAVASCRIPTS_DIRECTORY', 'javascripts');
define('LAYOUTS_TEMPLATE_DIRECTORY', 'layouts');
define('REPORTS_TEMPLATE_DIRECTORY', 'reports');
define('SHARED_TEMPLATE_DIRECTORY', 'shared');
define('STYLESHEETS_DIRECTORY', 'stylesheets');

require_once 'lib'.DS.'functions.php';
require_once 'lib'.DS.'environment.php';

spl_autoload_register('Environment::autoload');

Environment::append_include_path(APP_ROOT.DS.'lib');
Environment::append_include_path(dirname(__FILE__).DS.'lib');
Environment::append_include_path(dirname(__FILE__).DS.'lib'.DS.'database_adapters');

echo Dispatcher::dispatch(isset($_GET['url']) ? $_GET['url'] : '');