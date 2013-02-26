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

require_once 'front.php';

/**
 * Administrator dashboard controller class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/11/2012
 * @version    1.0
 */
class Administrator_Controller_Dashboard extends Administrator_Controller_Front
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  // ---------------------------------------------------------------------------

  /**
   * Dashboard page
   * @return void
   */
  public function index()
  {

  }

  // ---------------------------------------------------------------------------

  /**
   * Dashboard page
   * @return void
   */
  public function email()
  {
    $this->disableViews();
    $mail = Registry::factory('Mail_Php');
    $mail->to('sharapov.web@gmail.com', 'Александр');
    $mail->from('noreply@exidoengine.com', 'Exidoengine');
    $mail->reply('admin@exidoengine.com', 'Admin');
    $mail->subject('Тема');
    $mail->body('Текст письма');
    if($mail->send()) {
      print '1';
    } else {
      print '0';
    }
  }
}

?>