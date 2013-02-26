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
  'cookie_name'    => 'exido_session_id',
  'life_time'      => 3600, // Session life time in seconds
  'update_time'    => 300,
  'use_database'   => false, // Use DB for storing sessions (only for built-in session handler)
  'use_phpsession' => true, // Use standard PHP sessions handler instead built-in
  'db_table_name'  => 'session',
  'db_files_path'  => APPPATH.'data/cache',
  'cookie_path'    => '/',
  'cookie_domain'  => HOST,
  'time_reference' => 'time'
);

?>