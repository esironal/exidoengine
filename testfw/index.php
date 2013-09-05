<?php

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

// Set the constant in TRUE when production
define('IN_PRODUCTION', false);

// Set application environment
// Possible values: ADMINISTRATOR, FRONTEND, DEVELOPER
define('EXIDO_ENVIRONMENT_NAME', 'ADMINISTRATOR');

// Set absolute paths to directories without trailing slashes
$app_dir = ''; // Path to the App dir
$com_dir = ''; // Path to the Local dir
$sys_dir = '../lib/ex'; // Path to the System dir
$vnd_dir = '../vendors'; // Path to the Vendors dir

/*************************************************/
/* PLEASE DO NOT CHANGE ANYTHING UNDER THIS LINE */
/*************************************************/

// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)));

// Make the application relative to the docroot
if( ! is_dir($app_dir) and is_dir(DOCROOT.$app_dir))
  $app_dir = DOCROOT.$app_dir;

// Make the components relative to the docroot
if( ! is_dir($com_dir) and is_dir(DOCROOT.$com_dir))
  $com_dir = DOCROOT.$com_dir;

// Make the system relative to the docroot
if( ! is_dir($sys_dir) and is_dir(DOCROOT.$sys_dir)) {
  $sys_dir = DOCROOT.$sys_dir;
}

// Make the vendors relative to the docroot
if( ! is_dir($vnd_dir) and is_dir(DOCROOT.$vnd_dir)) {
  $vnd_dir = DOCROOT.$vnd_dir;
}

// Define the absolute paths for configured directories
define('APPPATH', str_replace('\\', '/', realpath($app_dir).'/'));
define('COMPATH', str_replace('\\', '/', realpath($com_dir).'/'));
define('SYSPATH', str_replace('\\', '/', realpath($sys_dir).'/'));
define('VNDPATH', str_replace('\\', '/', realpath($vnd_dir).'/'));

// Clean up the configuration vars
unset($app_dir, $com_dir, $sys_dir, $vnd_dir);

// Load the base, low-level functions
require APPPATH.'bootstrap.php';

?>