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
 * Administrator controller abstract class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    25/11/2012
 * @version    1.0
 */
abstract class Controller_Administrator_Abstract extends Controller_Abstract
{
  /**
   * Allow all the controllers to run in production by default
   * @var bool
   */
  private $_is_logged_in = false;

  // ---------------------------------------------------------------------------

  /**
   * Constructor. Check if the user is logged in.
   */
  public function __construct()
  {
    parent::__construct();

    // Get session data
    $_isAuth = $this->_isAuth();
    if(isset($_isAuth['password']) and $_isAuth['user_name']) {
      if($r = $this->model('Model_User')->getUser($_isAuth['user_name'], $_isAuth['password'])) {
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
        $this->_is_logged_in = true;
      }
    }

    $this->view->header_menu = array(
      uriSite('dashboard')   => __('Dashboard'),
      uriSite('user/list')   => __('Users'),
      uriSite('user/access') => __('Access'),
      uriSite('page/list')   => __('Static pages'),
      uriSite('site/config') => __('Site config'),
      uriSite('component')   => __('...')
    );
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if the user is logged in before the main method executes.
   * @return bool
   */
  public function beforeController()
  {
    if( ! $this->_is_logged_in) {
      $this->view->getView('action/auth/index');
    }
    return $this->_is_logged_in;
  }

  // ---------------------------------------------------------------------------

  /**
   * Return FALSE if the user is not logged in.
   * @return bool
   */
  protected function isLoggedIn()
  {
    return $this->_is_logged_in;
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if the user is authorized.
   * @return mixed
   */
  private function _isAuth()
  {
    return $this->session->get('logged_user');
  }
}

?>