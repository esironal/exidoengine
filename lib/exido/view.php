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

include_once 'view/helper.php';
include_once 'view/cache.php';
include_once 'view/abstract.php';
include_once 'view/helper/abstract.php';
include_once 'view/helper/library.php';
include_once 'view/helper/meta.php';
include_once 'view/helper/html.php';

/**
 * View class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    14/06/2010
 * @version    1.0
 */
class View extends View_Abstract
{
  /**
   * Special variable containing an action view content.
   */
  private $_actionContent;

  /**
   * Singleton instance
   * @var
   */
  private static $_instance;

  // ---------------------------------------------------------------------------

  /**
   * Gets the singleton instance
   * @return Input
   */
  public static function & instance()
  {
    if(self::$_instance === null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  public function addMethod($method, $callback)
  {
    // Todo: Make the methods for views
    //self::$_methods[$method] = $callback;
  }

  // ---------------------------------------------------------------------------

  /**
   * Adds array of variable in View object.
   * @param array $vars
   * @return void
   */
  public function addArray(array $vars)
  {
    foreach($vars as $key => $value) {
      $this->$key = $value;
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Set action content.
   * @param string $html
   * @return void
   */
  public function setActionContent($html)
  {
    $this->_actionContent = $html;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get action content.
   * @return string
   */
  public function getActionContent()
  {
    return $this->_actionContent;
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads a custom view. A view file loaded using this method will never been cached.
   * @param string $file
   * @param bool $return
   * @return string|bool
   */
  public function getDynamicView($file, $return = false)
  {
    if($return) {
      return $this->_addView($file, false);
    }
    print $this->_addView($file, false);
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads a custom view.
   * @param string $file
   * @param bool $return
   * @param int $cache_lifetime
   * @return string|bool
   */
  public function getView($file, $return = false, $cache_lifetime = null)
  {
    if($return) {
      return $this->_addView($file, false);
    }
    print $this->_addView($file, true, $cache_lifetime);
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads a custom view into the parent view.
   * @param string $file
   * @param bool $use_cache
   * @param int $cache_lifetime
   * @return string
   */
  private function _addView($file, $use_cache = true, $cache_lifetime = null)
  {
    // Check if the view is cached
    if($use_cache and $this->_cache_config->cache_enabled) {
      // Set specific cache life time
      if( ! empty($cache_lifetime)) {
        $this->_cache_config->cache_lifetime = $cache_lifetime;
      }
      $this->_cache_config->file = $file;
      // Trying to read cache
      $cache = new View_Cache_Read($this->_cache_config);
      if($cached_view = $cache->getCache()) {
        return $cached_view;
      }
    }

    // Load a custom view into the parent view
    $view = Registry::factory('View_Custom')->load($file);
    //print $file;
    // If a custom view has already been loaded
    if( ! $view) {
      // Return the message
      return sprintf(__('[View file %s has already been loaded]'), $file.'.php');
    }
    // Parse a custom view
    $result = $view->parse($file, $this, new View_Helper);
    // If cached enabled, so we need to write a parsed view
    if($use_cache and $this->_cache_config->cache_enabled) {
      // Set view data
      $this->_cache_config->data = $result;
      // Set view file name
      $this->_cache_config->file = $file;

      $cache = new View_Cache_Write($this->_cache_config);
      // Save cache
      $cache->setCache();
    }
    // Return the parsed result
    return $result;
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles view variables that do not exist.
   * @param string $var
   * @return bool|object|void
   */
  public function __get($var)
  {
    print sprintf(__('[Undefined property $view->%s]'), $var);
    return Registry::factory('View_Dummy');
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles view methods that do not exist.
   * @param string $method
   * @param array $args
   * @return void
   */
  public function __call($method, array $args)
  {
    print sprintf(__('[Undefined method %s(%s)]'), $method, implode(', ', $args));
  }
}
?>