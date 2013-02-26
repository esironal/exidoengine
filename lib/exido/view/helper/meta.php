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
 * View helper class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    17/09/2012
 * @version    1.0
 */
abstract class View_Helper_Meta extends View_Helper_Library
{
  /**
   * The alias of helper function htmlMetaDescription() for simple using in views.
   * @param string $text
   * @return View_Helper_Meta
   */
  public function description($text)
  {
    print htmlMetaDescription($text);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlMetaKeywords() for simple using in views.
   * @param string $text
   * @return View_Helper_Meta
   */
  public function keywords($text)
  {
    print htmlMetaKeywords($text);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlFavIcon() for simple using in views.
   * @param string $file
   * @param string $folder
   * @return View_Helper_Meta
   */
  public function fav($file = 'favicon', $folder = 'css/images')
  {
    print htmlFavIcon($file, $folder);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlMetaCharset() for simple using in views.
   * @param string $charset
   * @return View_Helper_Meta
   */
  public function charset($charset = '')
  {
    print htmlMetaCharset($charset);
    return $this;
  }
}
?>