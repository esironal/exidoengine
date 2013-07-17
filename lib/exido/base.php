<?php defined('SYSPATH') or die('No direct script access allowed.');

/*******************************************************************************
 * ExidoEngine Web-sites manager
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (3.0)
 * that is bundled with this package in the file license_en.txt
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

// Define core constants
define('EXIDO_VERSION',    '1.1');
define('EXIDO_CODENAME',   'Zeos');
define('EXIDO_ENGINE',     'ExidoEngine');
define('EXIDO_DEVELOPER',  'Sharapov A.');
define('EXIDO_LOGO_GUID',  'EXIDO92B444-74F7-5A49-F322-C0A6324A5DB5');
define('EXIDO_LOGO',       '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');

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
  define('PHP_MAJOR_VERSION',   $version[0]);
  define('PHP_MINOR_VERSION',   $version[2]);
  define('PHP_RELEASE_VERSION', $version[4]);
}

// The system can't run on PHP version lower than 5.2.4
if(PHP_VERSION_ID < 50204) {
  die('Please update a PHP version to 5.2.4 or higher. Stop working!');
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