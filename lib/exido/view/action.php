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
 * View action class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    17/09/2012
 * @version    1.0
 */
final class View_Action extends View_Abstract
{
  private $_file;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   */
  public function __construct()
  {
    parent::__construct();
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads an action view. Controller method.
   * @param string $controller
   * @param string $method
   * @return View_Action
   */
  public function load($controller, $method)
  {
    $this->_file = Exido::findFile('template/default/action/'.$controller.'/', $method);
    if($this->_file == null) {
      $this->_file = sprintf(__('[View file %s is not found]'), 'template/default/action/'.$controller.'/'.$method.'.php');
    }
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Parse a view.
   * @param View $view
   * @param View_Helper $helper
   * @return string
   */
  public function parse(View $view, View_Helper $helper)
  {
    // Check if the view is cached
    if($this->_cache_config->cache_enabled) {
      // Set specific cache life time
      if( ! empty($cache_lifetime)) {
        $this->_cache_config->cache_lifetime = $cache_lifetime;
      }
      $this->_cache_config->file = $this->_file;
      // Trying to read cache
      $cache = new View_Cache_Read($this->_cache_config);
      if($cached_view = $cache->getCache()) {
        return $cached_view;
      }
    }

    // Include the target file
    if(is_file($this->_file)) {
      // Start an output buffer
      ob_start();
      include $this->_file;
      // Return parsed content

      $result = ob_get_clean();
      // If cached enabled, so we need to write a parsed view
      if($this->_cache_config->cache_enabled) {
        // Set view data
        $this->_cache_config->data = $result;
        // Set view file name
        $this->_cache_config->file = $this->_file;

        $cache = new View_Cache_Write($this->_cache_config);
        // Save cache
        $cache->setCache();
      }
      return $result;
    }

    // Return message that the file does not found.
    return $this->_file;
  }
}

?>