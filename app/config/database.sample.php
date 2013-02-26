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
 * @license   http://exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

return array(
  'default' => array
  (
    'type'      => 'mysql',
    'user'      => 'root',
    'pass'      => '',
    'host'      => 'localhost',
    'database'  => '',
    'port'      => false,
    'socket'    => false,
    'benchmark' => true,
    'pconnect'  => false,
    'timeout'   => 60,
    'unavailable_die' => false, // Throw an error if the DB isn't available
    'cache_enabled'   => true, // Results caching
    'cache_folder'    => APPPATH.'data/cache', // Cache directory. Application path by default
    'cache_lifetime'  => 3600, // Cache life time in seconds
    'character_set'   => __('__dbcharset'), // Set the DB charset. Depends on language loaded.
    'dbcollation'     => __('__dbcollation'), // Set collation. Depends on language loaded.
    'lc_time_names'   => __('__dbtimenames'), // Set time names. Depends on language loaded.
    'time_zone'       => __('__dbtime_zone'), // Set time zone. Depends on language loaded.
    'table_prefix'    => ''
  )
);

?>