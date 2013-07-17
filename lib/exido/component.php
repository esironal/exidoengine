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
 * Component class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    1/09/2012
 * @version    1.0
 */
final class Component
{
  /**
   * Default config
   */
  public static $default_config = array(
    'has_backend'  => 1,
    'has_frontend' => 1,
    'is_visible_in_backend_menu' => 0,
    'is_enabled' => 0
  );

  /**
   * Loaded components
   * @var array
   */
  protected static $_components = array();

  /**
   * Path list
   * @var array
   */
  protected static $_paths = array();

  // ---------------------------------------------------------------------------

  /**
   * Returns a loaded components.
   * @return void
   * @throws Exception_Exido
   */
  public static function load()
  {
    // Get components list
    foreach(Exido::config('component') as $name_space => $path) {
      // Get path for custom components. System components are placed in core/exidoengine
      if(is_dir(COMPATH.$path)) {
        self::$_paths[] = COMPATH.$path.'/';
        self::$_paths[] = COMPATH.$path.'/'.strtolower(EXIDO_ENVIRONMENT_NAME).'/';
      } else {
        throw new Exception_Exido('Component %s is not found in path %s', array($path, $path));
      }
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Load paths of additional components.
   * @return void
   */
  public static function initialize()
  {
    if($paths = self::getPaths() and ! empty($paths)) {
      Exido::setIncludePaths($paths);
    }
    // Load components configurations
    foreach(Exido::config('component') as $name_space => $path) {
      // Get component configuration
      $config = Exido::config($name_space);
      // Set name
      $config['ui_name'] = (isset($config['ui_name'])) ? $config['ui_name'] : $name_space;
      // Assign component configuration
      self::$_components[$name_space] = ($config) ? (array)$config : self::$default_config;
      // Set component paths
      self::$_components[$name_space]['paths'] = array(
        1 => COMPATH.$path.'/',
        2 => COMPATH.$path.'/'.strtolower(EXIDO_ENVIRONMENT_NAME).'/'
      );
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a component paths.
   * @return array
   */
  public static function getPaths()
  {
    return self::$_paths;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a components.
   * @return array
   */
  public static function getComponents()
  {
    return self::$_components;
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