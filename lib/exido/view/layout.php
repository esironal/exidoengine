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
 * View layout class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    17/09/2012
 * @version    1.0
 */
final class View_Layout extends View_Abstract
{
  private $_file;
  private $_layout_path;
  private $_layout_name;

  // ---------------------------------------------------------------------------

  /**
   * Constructor. Sets the path and file name for layout.
   */
  final public function __construct()
  {
    $this->_layout_path = 'template/default/layout/';
    $this->_layout_name = '_blank';
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads a global view. Design theme.
   * @return View_Layout
   */
  public function load()
  {
    $this->_file = Exido::findFile($this->_layout_path, $this->_layout_name);
    if($this->_file == null)
      $this->_file = sprintf(__('[View file %s is not found]'), $this->_layout_path.$this->_layout_name.'.php');
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Set the special layout view for controller.
   * @param string $path
   * @param null $name
   * @return View_Layout
   */
  public function setLayout($path, $name = null)
  {
    if( ! empty($path))
      $this->_layout_path = 'template/'.rtrim($path, '/').'/';
    if($name != null)
      $this->_layout_name = $name;
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
    if(is_file($this->_file)) {
      // Start an output buffer
      ob_start();
      include $this->_file;
      // Return parsed content
      return ob_get_clean();
    }
    return $this->_file;
  }
}

?>