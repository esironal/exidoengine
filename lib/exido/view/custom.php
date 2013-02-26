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
 * View custom class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    17/09/2012
 * @version    1.0
 */
final class View_Custom extends View
{
  private $_files = array();

  // ---------------------------------------------------------------------------

  /**
   * Loads a custom view. Returns FALSE if the view has already been loaded.
   * @param string $file
   * @return View_Custom
   */
  public function load($file)
  {
    if(isset($this->_files[$file])) {
      return $this;
    }
    // Find a view file
    $this->_files[$file] = Exido::findFile('template/default', $file);
    if($this->_files[$file] == null) {
      $this->_files[$file] = sprintf(__('[View file %s is not found]'), 'template/default/'.$file.'.php');
    }
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Parse a view.
   * @param string $file
   * @param View $view
   * @param View_Helper $helper
   * @return string
   */
  public function parse($file, View $view, View_Helper $helper)
  {
    // Include the target file
    if(is_file($this->_files[$file])) {
      // Start an output buffer
      ob_start();
      include $this->_files[$file];
      // Return parsed content
      return ob_get_clean();
    }
    return $this->_files[$file];
  }
}

?>