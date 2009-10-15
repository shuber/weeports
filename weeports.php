<?php

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('PS')) define('PS', PATH_SEPARATOR);

define('PUBLIC_ROOT', dirname($_SERVER['SCRIPT_FILENAME']));
define('WEEPORTS_ROOT', dirname(PUBLIC_ROOT));

define('APP_ROOT', WEEPORTS_ROOT.DS.'app');
define('CONFIG_ROOT', APP_ROOT.DS.'config');
define('REPORTS_ROOT', APP_ROOT.DIRECTORY_SEPARATOR.'reports');
define('TEMPLATES_ROOT', APP_ROOT.DIRECTORY_SEPARATOR.'templates');

define('LOG_ROOT', WEEPORTS_ROOT.DIRECTORY_SEPARATOR.'log');
define('TMP_ROOT', WEEPORTS_ROOT.DIRECTORY_SEPARATOR.'tmp');
define('VENDOR_ROOT', WEEPORTS_ROOT.DIRECTORY_SEPARATOR.'vendor');

define('PATH_PREFIX', preg_replace('#/'.basename(PUBLIC_ROOT).'/'.basename($_SERVER['SCRIPT_NAME']).'#', '', $_SERVER['SCRIPT_NAME']));

define('IMAGES_DIRECTORY', 'images');
define('JAVASCRIPTS_DIRECTORY', 'javascripts');
define('LAYOUTS_TEMPLATE_DIRECTORY', 'layouts');
define('SHARED_TEMPLATE_DIRECTORY', 'shared');
define('STYLESHEETS_DIRECTORY', 'stylesheets');
define('VIEWS_TEMPLATE_DIRECTORY', 'views');

require_once 'lib'.DS.'functions.php';
require_once 'lib'.DS.'environment.php';

Environment::set_error_log(LOG_ROOT.DS.'error.log');

spl_autoload_register('Environment::autoload');
Environment::remove_include_path(VENDOR_ROOT);
Environment::append_include_path(WEEPORTS_ROOT.DS.'lib');
Environment::append_include_path(VENDOR_ROOT);
Environment::append_include_path(dirname(__FILE__).DS.'lib');
Environment::append_include_path(dirname(__FILE__).DS.'lib'.DS.'database_adapters');

echo Dispatcher::dispatch(isset($_GET['url']) ? $_GET['url'] : '');