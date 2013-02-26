<?php defined('SYSPATH') or die('No direct script access allowed.');

/*******************************************************************************
 * ExidoEngine Content Management System
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (3.0)
 * that is bundled with this package in the file license_en.txt
 * It is also available through the world-wide-web at this URL:
 * http://exidoengine.com/license/gpl-3.0.html
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
 * @license   http://exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

// Define core constants
define('CORE_VERSION',    '1.1');
define('CORE_CODENAME',   'Zeos');
define('CORE_ENGINE',     'ExidoEngine');
define('CORE_DEVELOPER',  'Sharapov A.');

define('SYS_BENCHMARK',  'BM_');

// File and directory modes
define('FILE_READ_MODE',  0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE',   0755);
define('DIR_WRITE_MODE',  0777);

// File stream modes
define('FOPEN_READ',                          'rb');
define('FOPEN_READ_WRITE',                    'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',      'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',                  'ab');
define('FOPEN_READ_WRITE_CREATE',             'a+b');
define('FOPEN_WRITE_CREATE_STRICT',           'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',      'x+b');

// End Of Line delimiter
define('EXIDO_EOL', PHP_EOL);

// PHP_VERSION_ID is available as of PHP 5.2.7, if our
// version is lower than that, then emulate it
if( ! defined('PHP_VERSION_ID')) {
  $version = PHP_VERSION;
  define('PHP_VERSION_ID', ($version[0] * 10000 + $version[2] * 100 + $version[4]));
}

// The system can't run on PHP version lower than 5.2.4
if(PHP_VERSION_ID < 50204) {
  die('Please update a PHP version to 5.2.4 or higher. Stop working!');
}

if(PHP_VERSION_ID < 50207) {
  define('PHP_MAJOR_VERSION',   $version[0]);
  define('PHP_MINOR_VERSION',   $version[2]);
  define('PHP_RELEASE_VERSION', $version[4]);
}

include_once 'function.inc.php';
// Load empty core extension
include_once 'exido.php';
// Load additional libraries
include_once 'i18n.php';
include_once 'helper.php';
include_once 'model.php';
include_once 'model/mapper.php';
include_once 'model/eav.php';
include_once 'model/registry.php';
include_once 'component.php';
include_once 'debug.php';
include_once 'exception/exido.php';
include_once 'event.php';
include_once 'router.php';
include_once 'log.php';
include_once 'config.php';
include_once 'view.php';
include_once 'controller.php';
include_once 'uri.php';
include_once 'registry.php';
include_once 'input.php';
include_once 'vendor.php';
include_once 'session.php';

?>