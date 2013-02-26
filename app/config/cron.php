<?php defined('SYSPATH') or die('No direct script access allowed.');

/*******************************************************************************
 * ExidoEngine Content Management System
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (3.0)
 * that is bundled with this package in the file license_gpl.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@exidoengine.com so we can send you a copy immediately.
 *
 * @license   http://www.gnu.org/copyleft/gpl.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

return array(
  // Enable system cron
  'cron_enabled'    => false,
  // List of allowed IPs.
  // Can be an array of IPs, e.g. array('10.23.10.23', '34.56.67.45-192.178.34.42')
  // Can contain the masks, e.g. '10.23.*.*'
  'cron_allowed_ip' => '*.*.*.*',
  'cron_job_list'   => array(
    // Here is the cron job list
    // [Job name] => [param array]
    'clear_expired_cache' => array(
      // Example of job definition:
      // .---------------- minute (0 - 59)
      // |  .------------- hour (0 - 23)
      // |  |  .---------- day of month (1 - 31)
      // |  |  |  .------- month (1 - 12) (from January = 1 to December = 12)
      // |  |  |  |  .---- day of week (1 - 7) (from Monday = 1 to Sunday = 7)
      // |  |  |  |  |
      // *  *  *  *  *
      'starting_at' => '2 1 * * *',
      // Functions that will be called during the job
      // For example: 'callback' => array('Function_Name', 'Second_Function_Name', etc... )
      // Also the model methods can be declared
      // For example: 'callback' => array('Model_Name:Method_Name', 'Second_Model_Name:Method_Name', etc... )
      'callback'    => array('Model_Cron:clearCache')
    )
  )
);

?>