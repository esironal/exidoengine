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
 * Administrator page controller class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/11/2012
 * @version    1.0
 */
class Administrator_Controller_User_Action extends Controller_Administrator_Abstract
{
  public $db_user;

  // ---------------------------------------------------------------------------

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    Helper::load('form', 'date', 'guid');
    // Load model
    $this->db_user= $this->model('Model_User');
  }

  // ---------------------------------------------------------------------------

  /**
   * Users index page
   * @return void
   */
  public function index()
  {
    $this->view->item_list = $this->db_user->getUserList();
  }

  // ---------------------------------------------------------------------------

  /**
   * Users add page
   * @return void
   */
  public function create()
  {
    // Save when posting
    if($this->input->checkPost()) {
      // Init validation object
      $v = Registry::factory('Validation_Form');
      // Set rules
      $v->setRule('user_name', 'required|alphaDash');
      $v->setRule('user_email', 'required|email');
      $v->setRule('role_name', 'required');
      // Set error messages
      $v->setRuleError('user_name', __('Please enter a user name'), 'required');
      $v->setRuleError('user_name', __('User name may contains only latin characters and numbers'), 'alphaDash');
      $v->setRuleError('user_email', __('Please enter a email'), 'required');
      $v->setRuleError('user_email', __('Please enter a valid email'), 'email');
      $v->setRuleError('role_name', __('Please choose role'), 'required');
      // Run validator
      if($v->run()) {
        // If validation was passed successfully
        $this->db_user->setUser_name($this->input->post('user_name'));
        $this->db_user->setUser_email($this->input->post('user_email'));
        $this->db_user->setRole_key($this->input->post('role_name'));
        $this->db_user->setDescription($this->input->post('description'));
        // Generate unique session id
        $this->db_user->setUnique_session_id(guidGet(64, 1, '', true));
        // Set owner parameters
        $this->db_user->setOwner_id(constant('@SU.USER_ID'));
        $this->db_user->setOwner_name(constant('@SU.USER_NAME'));
        $this->db_user->setGroup_id(constant('@SU.GROUP_ID'));
        $this->db_user->setGroup_name(constant('@SU.GROUP_NAME'));
        $this->db_user->setPermissions_owner('rwx');
        $this->db_user->setPermissions_group('r--');
        $this->db_user->setPermissions_other('r--');
        // Set date and status
        $this->db_user->setCreated_at(dateConvert2SQL());
        if($this->input->post('is_enabled')) {
          $this->db_user->setIs_enabled(1);
        } else {
          $this->db_user->setis_enabled(0);
        }
        // Generate password
        $password = $this->input->post('password');
        if($password) {
          $this->db_user->setPassword(md5($password));
        } else {
          $password = guidGet(8, 1, '', true);
          $this->db_user->setPassword(md5($password));
        }

        // Adding user
        if($this->db_user->addUser()) {
          $this->session->set('action_success', __('User has been successfully added.'));
          // Email password to user
          if($this->input->post('do_not_email_password') == false) {
            $this->_emailPassword($this->input->post('user_email'), $password);
          }
        } else {
          // If something was wrong
          $this->session->set('action_error',
                                sprintf(__('There is an error while creating a user. Details: %s'), $this->db_page->getErrorString())
          );
        }
      } else {
        // If something was wrong during validation
        $this->session->set('action_error',
                            sprintf(__('There is an error while creating a user. Details: %s'), $v->getErrorString())
        );
      }
      uriSiteRedirect('user');
    }
    $this->view->roles_list = $this->db_user->getRoleList();
  }

  // ---------------------------------------------------------------------------

  /**
   * Pages edit page
   * @param int $entity_id
   * @return void
   * @throws Exception_Exido
   */
  public function edit($entity_id = null)
  {
    // Get page data
    if( ! $this->view->attribute_form = $this->db_page->getEntityById($entity_id)) {
      throw new Exception_Exido(__('Page not found'), array(), 404);
    };
    // Save when posting
    if($this->input->checkPost()) {
      $post = $this->input->post();
      if($this->db_page->editAttributeValues($entity_id, $post)) {
        // Errors
        $this->session->set('action_success', __('Page has been successfully saved.'));
      } else {
        // Error when saving
        $this->session->set('action_error',
                              sprintf(__('There is an error while saving a page. Details: %s'), $this->db_page->getErrorString())
        );
      }
      uriSiteRedirect('page');
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Pages edit page
   * @param int $entity_id
   * @return void
   * @throws Exception_Exido
   */
  public function remove($entity_id = null)
  {
    $this->disableViews();
    // Get page data
    if( ! $this->view->attribute_form = $this->db_page->getEntityById($entity_id)) {
      throw new Exception_Exido(__('Page not found'), array(), 404);
    };

    if($this->db_page->removeEntity($entity_id)) {
      // Errors
      $this->session->set('action_success', __('Page has been successfully removed.'));
    } else {
      // Error when removing
      $this->session->set('action_error',
                          sprintf(__('There is an error while removing a page. Details: %s'), $this->db_page->getErrorString())
      );
    }
    uriSiteRedirect('page');
  }

  // ---------------------------------------------------------------------------

  /**
   * Send password to email
   * @param string $to
   * @param string $password
   * @return void
   * @throws Exception_Exido
   */
  private function _emailPassword($to, $password)
  {
    $this->view->password = $password;
    $email = Registry::factory('Mail_Php');
    $email->to($to);
    $email->from('', '');
    $email->subject(__('Your password'));
    $email->body($this->view->getView('mail/notification/password', true));
    return $email->send();
  }
}

?>