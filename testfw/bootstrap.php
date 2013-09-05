<?php defined('SYSPATH') or die('No direct script access allowed.');

/*******************************************************************************
 * ExidoEngine Web-sites manager
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (3.0)
 * that is bundled with this package in the file license_en.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.exidoengine.com/license/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@exidoengine.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade ExidoEngine to newer
 * versions in the future. If you wish to customize ExidoEngine for your
 * needs please refer to http://www.exidoengine.com for more information.
 *
 * @license   http://www.exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2013, ExidoEngine Solutions
 * @link      http://www.exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

// Enabling/disabling errors showing
if(IN_PRODUCTION == true)
  ini_set('display_errors', 0);
else
  ini_set('display_errors', 1);

// Set include paths
set_include_path(SYSPATH.PATH_SEPARATOR.
                   COMPATH.PATH_SEPARATOR.
                   VNDPATH.PATH_SEPARATOR.
                   APPPATH.PATH_SEPARATOR.
                   get_include_path()
);
print (get_include_path());
// Set web root path. It's empty by default.
// Usefull if ExidoEngine is installed NOT in the web-root directory.
define('WEB_ROOT', '');
// Set domain name
define('HOME', 'http://'.$_SERVER['SERVER_NAME'].'/'.WEB_ROOT);
// Set host name
define('HOST', $_SERVER['HTTP_HOST']);

// Load framework base functions
include_once 'Main.php';

// Set the PHP error reporting level.
// @see  http://php.net/error_reporting
error_reporting(E_ALL & ~E_DEPRECATED);

// Check if an installation folder is exists
//if(file_exists(APPPATH.'install/index.php')) {
  //include_once APPPATH.'install/index.php';
//}

// Set error handlers
set_error_handler    (array('Exido_Exception', 'handlerError'));
set_exception_handler(array('Exido_Exception', 'handlerException'));

// Initialize framework
Exido_Main_Core::initialize();

// Load basic include paths
Exido_Main_Core::setIncludePaths();

// You can attach a log writer by uncomment next line
//Exido::$log->attach(new Log_File(APPPATH.'data/cache/log'));

// Load additional components
//Component::load();
// Initialize loaded components
//Component::initialize();

// Include internalization languages
//Exido::$i18n->attach(new I18n_File('en_US'));
Exido::$i18n->attach(new Exido_I18n('ru_RU'));

// Set application time zone. Depends on language loaded.
// It's UTC default.
// @see  http://php.net/timezones
date_default_timezone_set(__('__time_zone'));

// Set application locale. Depends on language loaded.
// It's en_US.UTF-8 by default.
// @see  http://php.net/setlocale
setlocale(LC_ALL, __('__locale'));

// Set application charset. Depends on language loaded.
// It's UTF-8 default.
header('Content-Type: text/html; charset='.__('__charset'), true);

// Determine routing
Event::run('system.routing');

// System ready
Event::run('system.ready');

// Make the magic happen!
Event::run('system.execute');

// Clean up and exit
Event::run('system.shutdown');

?>