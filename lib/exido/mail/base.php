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
 * Mailer class.
 * @package    core
 * @subpackage mail
 * @copyright  Sharapov A.
 * @created    29/01/2010
 * @version    1.0
 */
abstract class Mail_Base
{
  public $useragent   = 'ExidoEngine-Mailer';
  public $mailtype    = 'html'; // text/html  a letter format
  public $charset     = 'utf-8'; // Default charset: iso-8859-1 or us-ascii
  public $priority    = '3'; // Default priority (1 - 5)
  public $eol         = "\r\n"; // A newline identifier
  public $subject     = '';
  public $body        = '';
  public $destination = array();

  protected $priorities    = array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');
  protected $mailtypes     = array('text', 'html');
  protected $headers       = array();

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param null $params
   */
  public function __construct($params = null)
  {
    if($params == null) {
      $params = array();
    }
    $this->setup($params);
  }

  // ---------------------------------------------------------------------------

  /**
   * Setup mail preferences.
   * @param array $params
   * @return void
   */
  public function setup(array $params)
  {
    // Convert array elements into class properties
    if(count($params) > 0) {
      foreach($params as $key => $val) {
        $this->$key = $val;
      }
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Resets values in case this class is used in a loop.
   * @return void
   */
  public function clear()
  {
    $props = array('body', 'subject');
    foreach($props as $val) {
      $this->$val = '';
    }
    $this->charset     = 'utf-8';
    $this->mailtype    = 'html';
    $this->useragent   = 'ExidoEngine-Mailer';
    $this->priority    = 3;
    $this->destination = array();
    $this->_clearHeaders();
  }

  // ---------------------------------------------------------------------------

  /**
   * Set receiver's email.
   * @param string $to
   * @param string $name
   * @return bool
   */
  public function to($to, $name = '')
  {
    if( ! $this->_validateEmail($to)) {
      return false;
    }
    if($name != '') {
      $name = $this->_prepareBase64($name);
    }
    $this->_setHeader('To', $name.' <'.$to.'>');
    $this->destination[] = $to;
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Set sender's email.
   * @param string $from
   * @param string $name
   * @return bool
   */
  public function from($from, $name = '')
  {
    if( ! $this->_validateEmail($from)) {
      return false;
    }
    if($name != '') {
      $name = $this->_prepareBase64($name);
    }
    $this->_setHeader('From', $name.' <'.$from.'>');
    $this->_setHeader('Return-Path', $from);
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Set Reply-to field.
   * @param string $reply_to
   * @param string $name
   * @return bool
   */
  public function reply($reply_to, $name = '')
  {
    if( ! $this->_validateEmail($reply_to)) {
      return false;
    }
    // Prepare the display name
    if($name != '') {
      $name = $this->_prepareBase64($name);
    }
    $this->_setHeader('Reply-To', $name.' <'.$reply_to.'>');
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Set subject.
   * @param string $subject
   * @return void
   */
  public function subject($subject)
  {
    $subject       = $this->_prepareBase64($subject);
    $this->subject = $subject;
  }

  // ---------------------------------------------------------------------------

  /**
   * Set message body
   * @param string $body
   * @return void
   */
  public function body($body)
  {
    $this->body = $body;
  }

  // ---------------------------------------------------------------------------

  /**
   * Validates an email.
   * @param string $email
   * @return bool
   */
  protected function _validateEmail($email)
  {
    if( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
      return false;
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Base64 preparing.
   * @param string $str
   * @return string
   */
  protected function _prepareBase64($str)
  {
    return '=?'.$this->charset.'?b?'.base64_encode($str).'?=';
  }

  // ---------------------------------------------------------------------------

  /**
   * Add a header item.
   * @param string $header
   * @param string $value
   * @return void
   */
  protected function _setHeader($header, $value)
  {
    $this->headers[$header] = $value;
  }

  // ---------------------------------------------------------------------------

  /**
   * Clear headers.
   * @return void
   */
  protected function _clearHeaders()
  {
    $this->headers = array();
  }

  // ---------------------------------------------------------------------------

  /**
   * Check the type of message. Returns TRUE if the message is plain text.
   * @return bool
   */
  protected function _isPlainMessage()
  {
    if($this->mailtype == 'text') {
      return true;
    } else {
      return false;
    }
  }
}

?>