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
 * View helper class. Provides the simplest including of Google hosted JS libraries.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    17/09/2012
 * @version    1.0
 */
abstract class View_Helper_Library extends View_Helper_Abstract
{
  /**
   * Target URL.
   * @var string
   */
  private $_url = '//ajax.googleapis.com/ajax/libs/';

  // -----------------------------------------------------------------------------

  /**
   * Adds the jquery include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function jquery($version)
  {
    $this->_getRemoteJs('jquery.min', $this->_url.'jquery/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the jquery UI include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function jqueryUI($version)
  {
    $this->_getRemoteJs('jquery-ui.min', $this->_url.'jqueryui/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the angularJS include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function angularJS($version)
  {
    $this->_getRemoteJs('angular.min', $this->_url.'angularjs/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the Chrome Frame include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function chromeFrame($version)
  {
    $this->_getRemoteJs('CFInstall.min', $this->_url.'chrome-frame/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the Dojo include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function dojo($version)
  {
    $this->_getRemoteJs('dojo', $this->_url.'dojo/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the Ext Core include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function extCore($version)
  {
    $this->_getRemoteJs('ext-core', $this->_url.'ext-core/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the MooTools include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function mootools($version)
  {
    $this->_getRemoteJs('mootools-yui-compressed', $this->_url.'mootools/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the Prototype include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function prototype($version)
  {
    $this->_getRemoteJs('prototype', $this->_url.'prototype/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the script.aculo.us include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function scriptaculous($version)
  {
    $this->_getRemoteJs('scriptaculous', $this->_url.'scriptaculous/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the SWFObject include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function swfobject($version)
  {
    $this->_getRemoteJs('swfobject', $this->_url.'swfobject/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Adds the WebFont Loader include tag.
   * @param string $version
   * @return View_Helper_Library
   */
  public function webfontloader($version)
  {
    $this->_getRemoteJs('webfont', $this->_url.'webfont/'.$version);
    return $this;
  }

  // -----------------------------------------------------------------------------

  /**
   * Loads a library from the remote resource.
   * @param string $js
   * @param string $path
   * @return void
   */
  private function _getRemoteJs($js, $path)
  {
    print '<script src="'.rtrim($path, '/').'/'.$js.'.js'.'"></script>'.EXIDO_EOL;
  }


}

?>