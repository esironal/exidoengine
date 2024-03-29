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
 * @license   http://www.exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2013, ExidoEngine Solutions
 * @link      http://www.exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

return array(
  'default' => array
  (
    'type'      => 'mysql',
    'user'      => 'root',
    'pass'      => '123',
    'host'      => 'localhost',
    'database'  => 'exidoengine_eav_dev',
    'port'      => false,
    'socket'    => false,
    'benchmark' => true,
    'pconnect'  => false,
    'timeout'   => 60,
    /**
     * Throw an error if the DB isn't available
     */
    'unavailable_die' => false,
    /**
     * Enable results caching
     */
    'cache_enabled'   => false,
    /**
     * Folder for storing cached files. Application path by default
     */
    'cache_folder'    => APPPATH.'data/cache',
    /**
     * Cache life time in seconds
     */
    'cache_lifetime'  => 3600,
    /**
     * Set DB charset, collation, time names and time zone
     * By default it all depends on language loaded
     */
    'character_set'   => __('__db_charset'),
    'db_collation'    => __('__db_collation'),
    'lc_time_names'   => __('__db_time_names'),
    'time_zone'       => __('__db_time_zone'),
    'table_prefix'    => ''
  )
);

?>