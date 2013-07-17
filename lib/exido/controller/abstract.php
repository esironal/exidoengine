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

require_once 'controller/interface/abstract.php';

/**
 * Base controller class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
abstract class Controller_Abstract implements Controller_Interface_Abstract
{
  /**
   * Allow all the controllers to run in production by default
   */
  const ALLOW_PRODUCTION = true;

  /**
   * URI instance
   * @var Uri
   */
  public $uri;

  /**
   * Input instance
   * @var Input
   */
  public $input;

  /**
   * View instance
   * @var View
   */
  public $view;

  /**
   * Prevent after controller
   * @var bool
   */
  public static $preventAfterController;

  /**
   * Current system user
   * @var string
   */
  protected $_system_user;

  /**
   * Current system user access
   * @var string
   */
  protected $_system_user_access;

  /**
   * Installed components
   * @var null
   */
  protected $_components = null;

  /**
   * The list of supported environments
   * @var array
   */
  private $_envs = array('DEVELOPER', 'ADMINISTRATOR', 'FRONTEND', 'PUBLISHER');

  /**
   * Loaded models registry
   * @var bool|object
   */
  private $_model;

  /**
   * Action view object
   * @var View
   */
  private $_viewAction;

  /**
   * Layout view object
   * @var View
   */
  private $_viewLayout;

  /**
   * Layout view status
   * @var bool
   */
  private $_disableLayoutView = false;

  /**
   * Action view status
   * @var bool
   */
  private $_disableActionView = false;

  /**
   * Return an action view
   * @var bool|string
   */
  private $_returnActionView = false;

  // ---------------------------------------------------------------------------

  /**
   * Loads URI and Input objects into the controller.
   * Check if the requested environment supports by the system.
   * @throws Exception_Exido
   */
  public function __construct()
  {
    // Check environment
    if( ! in_array(EXIDO_ENVIRONMENT_NAME, $this->_envs)) {
      throw new Exception_Exido('The requested environment %s does not supported by the system', array(EXIDO_ENVIRONMENT_NAME));
    }
    // URI should always be available
    $this->uri    = Uri::instance();
    // Input should always be available
    $this->input  = Input::instance();
    // Init a model loader object
    $this->_model = Registry::factory('Model');
    // Input should always be available
    $this->view   = View::instance();
    // Session object
    $this->session = Registry::factory('Session');
    // Init layout view object
    $this->_viewLayout = Registry::factory('View_Layout');
    // Init action view object
    $this->_viewAction = Registry::factory('View_Action');

    // Get active components
    $this->_components = $this->model('Model_Component')->getActiveComponents();
    // Get session data
    $_user_key = $this->_getCurrentUser();
    // If current user is unknown so we should start guest session
    if(empty($_user_key)) {
      // Guest session id
      $_user_key = '5627a272bf2563cee5877539bd906e7cc3eb33afcefe2b570a08906f9a34ae48';
    }
    if($r = $this->model('Model_User')->getUserByUniqueKey($_user_key)) {
      $this->_system_user = array(
        'user_id'     => $r->getUser_id(),
        'user_name'   => $r->getUser_name(),
        'password'    => $r->getPassword(),
        'user_email'  => $r->getUser_email(),
        'owner_id'    => $r->getOwner_id(),
        'owner_name'  => $r->getOwner_name(),
        'group_id'    => $r->getGroup_id(),
        'group_name'  => $r->getGroup_name(),
        'role_name'   => $r->getRole_key(),
        'permissions' => array(
          'owner' => $r->getPermissions_owner(),
          'group' => $r->getPermissions_group(),
          'other' => $r->getPermissions_other()
        ),
        'is_enabled'  => $r->getIs_enabled(),
        'is_dropped'  => $r->getIs_dropped(),
        'is_system'   => $r->getIs_system()
      );

      // User constants
      define('@SU.GROUP_ID'   , $r->getGroup_id());
      define('@SU.GROUP_NAME' , $r->getGroup_name());
      define('@SU.USER_ID'    , $r->getUser_id());
      define('@SU.USER_NAME'  , $r->getUser_name());

      $this->_system_user_access = $this->model('Model_User')->getUserAccess($r->getUser_id(), EXIDO_ENVIRONMENT_NAME);
      $this->session->set('system_user', $_user_key);
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Disable all the views for method.
   * @return void
   */
  protected function disableViews()
  {
    $this->disableActionView();
    $this->disableLayoutView();
  }

  // ---------------------------------------------------------------------------

  /**
   * Disable the action view showing.
   * @return void
   */
  protected function disableActionView()
  {
    $this->_disableActionView = true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Disable the layout view showing.
   * @return void
   */
  protected function disableLayoutView()
  {
    $this->_disableLayoutView = true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Disable the execution of "After" controller method.
   * Can be executed in "Before" or main controller methods.
   * @return void
   */
  protected function preventAfter()
  {
    self::$preventAfterController = true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Set the special layout view for controller action.
   * @param string $path
   * @param null $name
   * @return void
   */
  protected function setForceLayoutView($path, $name = null)
  {
    $this->_viewLayout->setLayout($path, $name);
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads a model.
   * @param string $model
   * @param null $params
   * @return mixed
   */
  protected function model($model, $params = null)
  {
    return $this->_model->load($model, $params);
  }

  // ---------------------------------------------------------------------------

  /**
   * Automatically executed before the controller action. Can be used to set
   * class properties, do authorization checks, and execute other custom code.
   * NOTE: controller method will fail if the method returns FALSE.
   * This method can't be executed directly.
   * @return bool
   */
  public function beforeController()
  {
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Automatically executed after the controller action. Can be used to apply
   * transformation to the request response, add extra output, and execute
   * other custom code. This method can't be executed directly.
   * @return bool
   */
  public function afterController()
  {
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Automatically executed after all the controller actions.
   * This method can't be executed directly.
   * @return void
   */
  public function pushLayoutController()
  {
    $action_content = $this->view->getActionContent();
    // If an action view still not was generated, so we need to generate it
    if($this->_returnActionView == false and empty($action_content)) {
      // If the action view is enabled. Prints it.
      if($this->_disableActionView == false) {
        // Load an action view to the View object
        $this->_viewAction->load(Router::$controller_view, Router::$method);
        // Parse action view
        $action_content = $this->_viewAction->parse($this->view, new View_Helper);
        // Set parsed action view
        $this->view->setActionContent($action_content);
      } else
        $this->view->setActionContent('');
    }
    // If the layout view is enabled. Prints it.
    if($this->_disableLayoutView == false) {
      // Load the layout template
      $this->_viewLayout->load();
      // Print the layout
      print $this->_viewLayout->parse($this->view, new View_Helper);
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the action view HTML.
   * @param bool $print
   * @return bool|string
   */
  public function getActionView($print = false)
  {
    $this->_returnActionView   = true;
    // Load an action view to the View object
    $this->_viewAction->load(Router::$controller_view, Router::$method);
    // Parse action view
    $action_content = $this->_viewAction->parse($this->view, new View_Helper);
    // Set parsed action view
    $this->view->setActionContent($action_content);
    if($print) {
      print $this->view->getActionContent();
      return true;
    }
    return $this->view->getActionContent();
  }

  // ---------------------------------------------------------------------------

  /**
   * Prints the action view HTML.
   * @return string
   */
  public function printActionView()
  {
    $this->getActionView(true);
  }

  // ---------------------------------------------------------------------------

  /**
   * Get current user.
   * @return mixed
   */
  private function _getCurrentUser()
  {
    return $this->session->get('system_user');
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles methods that do not exist.
   * @param string $method
   * @param array $args
   * @return void
   * @throws Exception_Exido_404
   */
  public function __call($method, array $args)
  {
    throw new Exception_Exido_404('Undefined method %s(%s)', array($method, implode(', ', $args)));
  }
}

?>