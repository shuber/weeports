<?php

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('PS')) define('PS', PATH_SEPARATOR);

define('PUBLIC_ROOT', dirname($_SERVER['SCRIPT_FILENAME']));
define('WEEPORTS_ROOT', dirname(PUBLIC_ROOT));

define('APP_ROOT', WEEPORTS_ROOT.DS.'app');
define('REPORTS_ROOT', APP_ROOT.DS.'reports');
define('TEMPLATES_ROOT', APP_ROOT.DS.'templates');

define('CONFIG_ROOT', WEEPORTS_ROOT.DS.'config');
define('LIB_ROOT', WEEPORTS_ROOT.DS.'lib');
define('LOG_ROOT', WEEPORTS_ROOT.DS.'log');
define('TMP_ROOT', WEEPORTS_ROOT.DS.'tmp');
define('VENDOR_ROOT', WEEPORTS_ROOT.DS.'vendor');

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
Environment::append_include_path(LIB_ROOT);
Environment::append_include_path(VENDOR_ROOT);
Environment::append_include_path(dirname(__FILE__).DS.'lib');
Environment::append_include_path(dirname(__FILE__).DS.'lib'.DS.'database_adapters');

ConnectionManager::$configurations = Spyc::YAMLLoad(CONFIG_ROOT.DS.'database.yml');

echo Dispatcher::dispatch(isset($_GET['weeports_uri']) ? $_GET['weeports_uri'] : '');