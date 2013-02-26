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
 * Component class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    1/09/2012
 * @version    1.0
 */
final class Component
{
  /**
   * Loaded components
   * @var array
   */
  protected static $_components = array();

  // ---------------------------------------------------------------------------

  /**
   * Returns a loaded components.
   * @return void
   * @throws Exception_Exido
   */
  public static function readComponents()
  {
    // Get components list
    $_components = Exido::config('component');
    foreach($_components as $name => $path) {
      if(is_dir(COMPATH.$path)) {
        // Set component paths
        self::$_components[$name] = array(
          1 => COMPATH.$path,
          2 => COMPATH.$path.'/'.strtolower(EXIDO_ENVIRONMENT_NAME)
        );
      } else {
        throw new Exception_Exido('Component %s is not found in path %s', array($name, $path));
      }
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a component paths.
   * @return array
   */
  public static function getComponentPaths()
  {
    $paths = array();
    foreach(self::$_components as $path) {
      $paths[] = $path[1];
      $paths[] = $path[2];
    }
    return $paths;
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