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
   * Loaded components
   * @var array
   */
  protected static $_components;

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
    if(self::$_components == null)
      self::$_components = Registry::factory('Model_Component')->getActive();

    if(self::$_components) {
      // Get components list
      foreach(self::$_components as $component) {
        if($component->is_system == 0) {
          // Get path for custom components. System components are placed in core/exidoengine
          if(is_dir(COMPATH.$component->path)) {
            self::$_paths[] = COMPATH.$component->path.'/';
            self::$_paths[] = COMPATH.$component->path.'/'.strtolower(EXIDO_ENVIRONMENT_NAME).'/';
          } else {
            throw new Exception_Exido('Component %s is not found in path %s', array($component->component_key, COMPATH.$component->component_key));
          }
        }
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
    if($paths = self::getPaths() and ! empty($paths))
      Exido::setIncludePaths($paths);
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