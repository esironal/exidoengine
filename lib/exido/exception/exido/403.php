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
 * Error 403 class.
 * @package    core
 * @subpackage exception
 * @copyright  Sharapov A.
 * @created    26/10/2011
 * @version    1.0
 */
class Exception_Exido_403 extends Exception_Exido
{
  /**
   * Constructor.
   * @param string $message
   * @param array $vars
   */
  public function __construct($message = '', array $vars = array())
  {
    // Set default message
    if(empty($message)) {
      $message = __('Access forbidden');
    }
    // Pass the message and integer code to the parent
    parent::__construct($message, $vars, 403);
  }
}

?>