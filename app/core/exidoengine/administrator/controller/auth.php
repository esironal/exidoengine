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
 * Administrator authorisation controller class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/11/2012
 * @version    1.0
 */
final class Administrator_Controller_Auth extends Controller_Json_Abstract
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
    $uid = $this->input->post('uid');
    $pwd = $this->input->post('pwd');
    if($r = $this->model('Model_User')->getUser($uid, md5($pwd))) {
      $this->session->set('logged_user', array(
          'user_id'     => $r->getUser_id(),
          'user_name'   => $r->getUser_name(),
          'password'    => $r->getPassword(),
          'user_email'  => $r->getUser_email(),
          'owner_id'    => $r->getOwner_id(),
          'owner_name'  => $r->getOwner_name(),
          'group_id'    => $r->getGroup_id(),
          'group_name'  => $r->getGroup_name(),
          'role_name'   => $r->getRole_name(),
          'permissions' => array(
            'owner' => $r->getPermissions_owner(),
            'group' => $r->getPermissions_group(),
            'other' => $r->getPermissions_other()
           ),
          'is_enabled'  => $r->getIs_enabled(),
          'is_dropped'  => $r->getIs_dropped(),
          'is_system'   => $r->getIs_system()
        )
      );
      $this->jsonText('AUTH');
    } else {
      $this->jsonErrorCode('403');
      $this->jsonError(__('User is not found or blocked'));
    }
  }
}

?>