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

/**
 * Cron running class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/11/2012
 * @version    1.0
 */
class Frontend_Controller_Cron extends Controller
{
  /**
   * Started job list
   * @var array
   */
  private static $_has_run = array();

  /**
   * Cron log
   * @var array
   */
  private $_log = array();

  // ---------------------------------------------------------------------------

  /**
   * Execute cron tasks
   * @return bool
   */
  final public function index()
  {
    Helper::load('date');

    // Get config
    $cron = Exido::config('cron');
    if( ! $cron->cron_enabled) {
      print __('System cron disabled.');
      return false;
    }

    if( ! is_array($cron->cron_allowed_ip)) {
      $cron->cron_allowed_ip = array($cron->cron_allowed_ip);
    }

    // Get the client IP
    $ip_block = $this->input->ip(true);

    Helper::load('ip');
    foreach($cron->cron_allowed_ip as $ip) {
      if($range = ipRangeParser($ip)) {
        if( ! ipCheckRange($ip_block, $range[0], $range[1])) {
          if($range[0] == $range[1]) {
            print sprintf(__("Your IP %s doesn't match in allowed IP %s"), $this->input->ip(), $ip);
          } else {
            print sprintf(__("Your IP %s doesn't match in allowed range %s"), $this->input->ip(), $ip);
          }
          return false;
        }
      } else {
        print sprintf(__("Incorrect IP range %s"), $ip);
        return false;
      }
    }

    $local    = dateGetLocal('%M %H %e %m %u');
    $srv_time = explode(' ', $local);
    array_unshift($srv_time, $local);

    if(is_array($cron->cron_job_list)) {
      foreach($cron->cron_job_list as $job_name => $job_data) {

        if(isset(self::$_has_run[$job_name])) {
          continue;
        }

        $this->_log[$job_name] = '';

        // Check job time
        if( ! preg_match('/^([0-9\*]{1,2})\s([0-9\*]{1,2})\s([0-9\*]{1,2})\s([0-9\*]{1})\s([0-9\*]{1})$/',
                          $job_data['starting_at'],
                          $job_time)) {
          $this->_log[$job_name]['status'] = false;
          $this->_log[$job_name]['result'] = sprintf(__('Incorrect starting time for job %s'), $job_name);
          continue;
        }

        // Check day of week
        if(is_numeric($job_time[5]) and $srv_time[5] != $job_time[5]) {
          $this->_log[$job_name]['status'] = false;
          $this->_log[$job_name]['result'] = __("Is omitted due the week day doesn't match the scheduled day");
          continue;
        }
        // Check month
        if(is_numeric($job_time[4]) and $srv_time[4] != $job_time[4]) {
          $this->_log[$job_name]['status'] = false;
          $this->_log[$job_name]['result'] = __("Is omitted due the month doesn't match the scheduled month");
          continue;
        }
        // Check day of month
        if(is_numeric($job_time[3]) and $srv_time[3] != $job_time[3]) {
          $this->_log[$job_name]['status'] = false;
          $this->_log[$job_name]['result'] = __("Is omitted due the day of month doesn't match the scheduled day");
          continue;
        }
        // Check hour
        if(is_numeric($job_time[2]) and $srv_time[2] != $job_time[2]) {
          $this->_log[$job_name]['status'] = false;
          $this->_log[$job_name]['result'] = __("Is omitted due the hour doesn't match the scheduled hour");
          continue;
        }
        // Check minute
        if(is_numeric($job_time[1]) and $srv_time[1] != $job_time[1]) {
          $this->_log[$job_name]['status'] = false;
          $this->_log[$job_name]['result'] = __("Is omitted due the minute doesn't match the scheduled minute");
          continue;
        }

        // Here we go
        // Mark job as running
        self::$_has_run[$job_name] = $job_name;

        // Check the callback functions
        if(isset($job_data['callback']) and is_array($job_data['callback']) and ! empty($job_data['callback'])) {
          foreach($job_data['callback'] as $callback) {
            // Try to explode by ":"
            // If it is, so we're using a method from an object
            if($func = explode(':', $callback) and count($func) > 1) {
              // Call method
              $this->_log[$job_name][$callback]['status'] = true;
              $this->_log[$job_name][$callback]['result'] = $this->model($func[0])->$func[1]();
            } else {
              // Instead we're using a function
              // Call function
              if(function_exists($callback)) {
                $this->_log[$job_name][$callback]['status'] = true;
                $this->_log[$job_name][$callback]['result'] = $callback();
              } else {
                $this->_log[$job_name][$callback]['status'] = true;
                $this->_log[$job_name][$callback]['result'] = sprintf(__('Call to undefined cron function %s()'), $callback);
              }
            }
          }
        } else {
          $this->_log[$job_name]['status'] = false;
          $this->_log[$job_name]['result'] = __('Nothing to do');
        }
        unset(self::$_has_run[$job_name]);
      }
    }

    // TODO: Make the log showing
    pre($this->_log);
  }

  // ---------------------------------------------------------------------------

  /**
   * Get log.
   * @return array
   */
  protected function _getLog()
  {
    return $this->_log;
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents inheritance of the method.
   */
  final public function __construct()
  {
    $this->input  = Input::instance();
    $this->_model = Registry::factory('Model');
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents creation of views objects.
   */
  final public function pushLayoutController() { }

  // ---------------------------------------------------------------------------

  /**
   * Handles methods that do not exist.
   * @param string $method
   * @param array $args
   * @return void
   */
  public function __call($method, array $args)
  {
    // Nothing by default
  }
}

?>