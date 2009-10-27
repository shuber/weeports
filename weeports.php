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

$_SERVER['REQUEST_URI'] = preg_replace('#^https?://'.$_SERVER['HTTP_HOST'].'#', '', $_SERVER['REQUEST_URI']);
define('PATH_PREFIX', str_replace('/'.basename(PUBLIC_ROOT).'/'.basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));
define('REQUEST_URI', str_replace(PATH_PREFIX, '', $_SERVER['REQUEST_URI']));

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
Environment::append_include_path(dirname(__FILE__).DS.'lib');
Environment::append_include_path(dirname(__FILE__).DS.'lib'.DS.'database_adapters');
Environment::append_include_path(TEMPLATES_ROOT);

ConnectionManager::$configurations = Spyc::YAMLLoad(CONFIG_ROOT.DS.'database.yml');

$request = new Request(array_merge($_ENV, $_SERVER), $_GET, $_POST);
$request->path_prefix = PATH_PREFIX;

echo Dispatcher::dispatch($request);