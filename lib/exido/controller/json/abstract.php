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
 * Abstract json class. Manipulate with json response fields.
 * @package    core
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
abstract class Controller_Json_Abstract extends Controller_Ajax_Abstract
{
  private $_responseStatus = true;
  private $_responseText   = '';
  private $_responseCode   = null;
  private $_responseFields = array();

  // ---------------------------------------------------------------------------

  /**
   * Sets an error code.
   * @param int $code
   * @return void
   */
  protected function jsonErrorCode($code)
  {
    $this->_responseStatus = false;
    $this->_responseCode   = $code;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets a field to the response code.
   * @param string $key
   * @param mixed $value
   * @return void
   */
  protected function addResponseField($key, $value)
  {
    $this->_responseFields[$key] = $value;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets an error text.
   * @param string $string
   * @return void
   */
  protected function jsonError($string)
  {
    $this->_responseStatus = false;
    if($this->_responseCode == null) {
      $this->_responseCode = 500;
    }
    $this->_responseText   = $string;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets an error text.
   * @param string $string
   * @return void
   */
  protected function jsonText($string)
  {
    $this->_responseStatus = true;
    if($this->_responseCode == null) {
      $this->_responseCode = 200;
    }
    $this->_responseText   = $string;
  }

  // ---------------------------------------------------------------------------

  /**
   * Disable layout view for XML requests
   * @return void
   */
  public function beforeController()
  {
    $this->disableLayoutView();
  }

  // ---------------------------------------------------------------------------

  /**
   * Disable layout view for XML requests
   * @return void
   */
  public function afterController()
  {
    $response = array(
      'status' => $this->_responseStatus,
      'code'   => empty($this->_responseCode) ? 200 : $this->_responseCode,
      'text'   => empty($this->_responseText) ? $this->getActionView() : $this->_responseText
    );
    if( ! empty($this->_responseFields)) {
      $response = array_merge($response, $this->_responseFields);
    }
    print json_encode($response);
  }
}

?>