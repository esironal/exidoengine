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
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://www.exidoengine.com/
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
abstract class View_Helper_Html extends View_Helper_Meta
{
  /**
   * Loaded CSS files
   * @var array
   */
  private static $_css = array();

  /**
   * Loaded JS files
   * @var array
   */
  private static $_js  = array();

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlCSS() for simple using in views.
   * Default folder is WEB-ROOT/css/
   * @param string $file
   * @param string $folder
   * @return View_Helper_Html
   */
  public function css($file, $folder = 'css')
  {
    $folder = str_replace('\\', '/', $folder);
    $path   = rtrim($folder, '/').'/'.$file.'.css';
    self::$_css[$file] = $path;
    print htmlCSS($file, $folder);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlJS() for simple using in views.
   * Default script folder is WEB-ROOT/js/
   * @param string $file
   * @param string $folder
   * @return View_Helper_Html
   */
  public function js($file, $folder = 'js')
  {
    $folder = str_replace('\\', '/', $folder);
    $path   = rtrim($folder, '/').'/'.$file.'.js';
    self::$_js[$file] = $path;
    print htmlJS($file, $folder);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlA() for simple using in views.
   * @param string $url
   * @param string $title
   * @return View_Helper_Html
   */
  public function a($url, $title)
  {
    print htmlA($url, $title);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlIe() for simple using in views.
   * @param string $file
   * @param string $version
   * @param string $folder
   * @return View_Helper_Html
   */
  public function ie($file, $version = '*', $folder = 'css')
  {
    print htmlIe(htmlCSS($file, $folder), $version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlScript() for simple using in views.
   * Prints the result of that function.
   * @param string $code
   * @return View_Helper_Html
   */
  public function script($code)
  {
    print htmlScript($code);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlStyle() for simple using in views.
   * Prints the result of that function.
   * @param string $code
   * @return View_Helper_Html
   */
  public function style($code)
  {
    print htmlStyle($code);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlHeading() for simple using in views.
   * Prints the result of that function.
   * @param string $data
   * @param string $h
   * @return View_Helper_Html
   */
  public function heading($data = '', $h = '3')
  {
    print htmlHeading($data, $h);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlBR() for simple using in views.
   * Prints the result of that function.
   * @param int $num
   * @return View_Helper_Html
   */
  public function br($num = 1)
  {
    print htmlBR($num);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlHR() for simple using in views.
   * Prints the result of that function.
   * @param string $class
   * @return View_Helper_Html
   */
  public function hr($class = 'hr')
  {
    print htmlHR($class);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlNbs() for simple using in views.
   * Prints the result of that function.
   * @param int $num
   * @return View_Helper_Html
   */
  public function nbs($num = 1)
  {
    print htmlNbs($num);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlMsgBox() for simple using in views.
   * Prints the result of that function.
   * @param string $msg
   * @param string $class
   * @param bool $use_span set TRUE if you need to use span instead div
   * @return View_Helper_Html
   */
  public function notifier($msg, $class = '-i-box -i-simple-box', $use_span = false)
  {
    print htmlMsgBox($msg, $class, $use_span);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlDiv() for simple using in views.
   * Prints the result of that function.
   * @param string $id
   * @param string $class
   * @return View_Helper_Html
   */
  public function target($id, $class = '-i-simple-box')
  {
    print htmlDiv($id, $class);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlOpenDiv() for simple using in views.
   * Prints the result of that function.
   * @param string $id
   * @param string $class
   * @return View_Helper_Html
   */
  public function open($id = '', $class = '-i-simple-box')
  {
    print htmlOpenDiv($id, $class);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlCloseDiv() for simple using in views.
   * Prints the result of that function.
   * @param string $extra
   * @return View_Helper_Html
   */
  public function close($extra = '')
  {
    print htmlCloseDiv($extra);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlBase() for simple using in views.
   * Prints the result of that function.
   * @return View_Helper_Html
   */
  public function base()
  {
    print htmlBase();
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function __() for simple using in views.
   * Prints the result of that function.
   * @param string $line
   * @return void
   */
  public function line($line)
  {
    print __($line);
  }

  // ---------------------------------------------------------------------------

  /**
   * The alias of helper function htmlTitle() for simple using in views.
   * @param string $title
   * @return View_Helper_Html
   */
  public function title($title)
  {
    print htmlTitle($title);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlOpen() for simple using in views.
   * Prints the result of that function.
   * @return View_Helper_Html
   */
  public function openHtml()
  {
    print htmlOpen();
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlClose() for simple using in views.
   * Prints the result of that function.
   * @param string $extra
   * @return View_Helper_Html
   */
  public function closeHtml($extra = '')
  {
    print htmlClose($extra);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlHeadOpen() for simple using in views.
   * Prints the result of that function.
   * @return View_Helper_Html
   */
  public function openHead()
  {
    print htmlHeadOpen();
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlHeadClose() for simple using in views.
   * Prints the result of that function.
   * @param string $extra
   * @return View_Helper_Html
   */
  public function closeHead($extra = '')
  {
    print htmlHeadClose($extra);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlBodyOpen() for simple using in views.
   * Prints the result of that function.
   * @return View_Helper_Html
   */
  public function openBody()
  {
    print htmlBodyOpen();
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlBodyClose() for simple using in views.
   * Prints the result of that function.
   * @param string $extra
   * @return View_Helper_Html
   */
  public function closeBody($extra = '')
  {
    print htmlBodyClose($extra);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * The alias of helper function htmlDoctype() for simple using in views.
   * Prints the result of that function.
   * @return View_Helper_Html
   */
  public function doctype()
  {
    print htmlDoctype();
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Return the HEAD tag
   * @param string $str
   * @return View_Helper_Html
   */
  public function head($str)
  {
    print $this->openHead().$str.$this->closeHead();
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Return the HTML tag
   * @param string $str
   * @return View_Helper_Html
   */
  public function html($str)
  {
    print $this->openHtml().$str.$this->closeHtml();
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Return the BODY tag
   * @param string $str
   * @return View_Helper_Html
   */
  public function body($str)
  {
    print $this->openBody().$str.$this->closeBody();
    return $this;
  }
}
?>