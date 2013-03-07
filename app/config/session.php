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
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://www.exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

return array(
  'cookie_name'    => 'exido_session',
  /**
   * Session life time in seconds
   */
  'life_time'      => 3600,
  'update_time'    => 300,
  /**
   * Use DB for storing sessions (only for built-in session handler)
   */
  'use_database'   => false,
  /**
   * Use standard PHP sessions handler instead built-in handler
   */
  'use_phpsession' => true,
  /**
   * Session table name
   */
  'db_table_name'  => 'session',
  /**
   * Folder for storing session files
   */
  'db_files_path'  => APPPATH.'data/cache',
  'cookie_path'    => '/',
  'cookie_domain'  => HOST,
  'time_reference' => 'time'
);

?>