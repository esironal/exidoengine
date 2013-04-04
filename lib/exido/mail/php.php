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

require_once 'mail/base.php';

// -----------------------------------------------------------------------------

/**
 * Email class.
 * @package    core
 * @subpackage mail
 * @copyright  Sharapov A.
 * @created    16/02/2010
 * @version    1.0
*/
final class Mail_Php extends Mail_Base
{
  /**
   * Sends a message using PHP mail() function
   * @return bool
   */
  public function send()
  {
    if(count($this->destination) == 0) {
      return false;
    }

    // Set header
    $this->_setHeader('MIME-Version', '1.0');
    $this->_setHeader('X-Mailer', $this->useragent);
    $this->_setHeader('X-Priority', $this->priorities[$this->priority - 1]);
    // Use plain or html type of message
    if($this->_isPlainMessage()) {
      $this->_setHeader('Content-type', 'text/plain; charset='.$this->charset);
    } else {
      $this->_setHeader('Content-type', 'text/html; charset='.$this->charset);
    }

    // Set the destinations
    $destination = implode(', ', $this->destination);
    $subject     = $this->subject;
    $body        = $this->body;
    // Build headers in line
    $header = '';
    foreach($this->headers as $k => $v) {
      $header.= $k.': '.$v.$this->eol;
    }

    if( ! @mail($destination, $subject, $body, $header)) {
      return false;
    } else {
      return true;
    }
  }
}

?>