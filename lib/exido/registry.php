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

/**
 * Registry class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    14/06/2010
 * @version    1.0
 */
final class Registry
{
  /**
   * Loaded objects list
   * @var array
   */
  private static $_objects = array();

  // ---------------------------------------------------------------------------

  /**
   * Loads a class and instantiate an object.
   * @param string $class class name
   * @param null $params
   * @param bool $instantiate
   * @param bool $force_new_object
   * @return object|bool
   */
  public static function & factory($class, $params = null, $instantiate = true, $force_new_object = false)
  {
    $class = strtolower($class);
    $path  = str_replace('_', '/', $class);

    // If we would like to instantiate a new object,
    // we do not need to check the class existance.
    if($force_new_object) {
      // Does the class exist? If so, we're done...
      if(isset(self::$_objects[$class])) {
        return self::$_objects[$class];
      }
    }

    $p        = explode('/', $path);
    $filename = end($p);
    $path     = implode('/', array_slice($p, 0, -1)).'/';

    // Try to find a file
    $file = Exido::findFile($path, $filename, true);
    if(is_file($file)) {
      include_once $file;
      $name = $class;
      if($force_new_object) {
        Helper::load('guid');
        $name = $name.'_'.guidGet();
      }
      if($instantiate == false) {
        self::$_objects[$name] = true;
        return self::$_objects[$name];
      }
      self::$_objects[$name] = new $class($params);
      return self::$_objects[$name];
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an object by its class name.
   * @param string $class
   * @return object|bool
   */
  public static function getObject($class)
  {
    $class = strtolower($class);
    // Does the class exist?  If so, we're done...
    if(isset(self::$_objects[$class])) {
      return self::$_objects[$class];
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Drops an object by its class name.
   * @param string $class
   * @return bool
   */
  public static function dropObject($class)
  {
    $class = strtolower($class);
    // Does the class exist?  If so, we're done...
    if(isset(self::$_objects[$class])) {
      unset(self::$_objects[$class]);
      return true;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a loaded classes.
   * @return array
   */
  public static function getLoadedClasses()
  {
    return self::$_objects;
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object
   */
  final private function __construct()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object
   */
  final private function __clone()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }
}

?>