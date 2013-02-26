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
 * System events class. Supports a multiple number of events.
 * @package    core
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
final class Event
{
  /**
   * Events data
   * @var
   */
  public static $data;

  /**
   * Events list
   * @var array
   */
  private static $_events = array();

  /**
   * Started events list
   * @var array
   */
  private static $_has_run = array();

  // ---------------------------------------------------------------------------

  /**
   * Adds a system event.
   * @param string $name
   * @param string $callback http://php.net/callback
   * @return bool
   */
  public static function add($name, $callback)
  {
    if( ! isset(self::$_events[$name])) {
      // Create a new event
      self::$_events[$name] = array();
    } elseif (in_array($callback, self::$_events[$name], true)) {
      // Return false if the event exists
      return false;
    }
    self::$_events[$name][] = $callback;
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an event functions.
   * @param string $name
   * @return array
   */
  public static function get($name)
  {
    return empty(self::$_events[$name]) ? array() : self::$_events[$name];
  }

  // ---------------------------------------------------------------------------

  /**
   * Runs an event.
   * @param string $name
   * @param null $data
   * @return void
   */
  public static function run($name, $data = null)
  {
    if( ! empty(self::$_events[$name])) {
      self::$data = $data;
      $callbacks  = self::get($name);
      foreach($callbacks as $callback) {
        call_user_func($callback);
      }
      $clear_data = '';
      self::$data =& $clear_data;
    }
    // Mark event as running
    self::$_has_run[$name] = $name;
  }

  // ---------------------------------------------------------------------------

  /**
   * Protects to run an event twice. Return TRUE if an event is already running.
   * @param string $name
   * @return bool
   */
  public static function hasRun($name)
  {
    return isset(self::$_has_run[$name]);
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object.
   */
  final private function __construct()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object.
   */
  final private function __clone()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }
}

?>