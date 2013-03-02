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
  private $_has_access = false;

  private $_actions = array('r', 'w', 'x');

  // ---------------------------------------------------------------------------

  /**
   * Constructor. Check if the user is logged in.
   */
  public function __construct()
  {
    parent::__construct();

    // Configuring main menu
    $this->view->header_menu = array();
    if($this->_components != null and ! empty($this->_components)) {
      foreach($this->_components as $d) {
        $this->view->header_menu[uriSite($d->component_path)] = __($d->component_name);
      }
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if the user is logged in before the main method executes.
   * @return bool
   */
  public function beforeController()
  {
    $path = implode('/', uriSegments());
    if( ! $this->_checkAccess($path, 'r')) {
      $this->view->getView('action/auth/index');
      return false;
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Check access to the component
   * @param string $path
   * @param string $action
   * @return bool
   */
  protected function _checkAccess($path, $action)
  {
    // Check if an action allowed
    if( ! in_array($action, $this->_actions))
      return false;

    // If the user has full access
    if(isset($this->_system_user_access['ALL'])) {
      $access = $this->_system_user_access['ALL'];
    // If the user has access only to specified components
    } elseif(isset($this->_system_user_access[$path])) {
      $access = $this->_system_user_access[$path];
    } else
      // No access
      return false;

    // Check actions
    switch($action) {
      case 'r' : // Read action
          if($access[0] != 'r')
            return false;
        break;
      case 'w' : // Write action
          if($access[1] != 'w')
            return false;
        break;
      case 'x' : // Execute action
          if($access[2] != 'x')
            return false;
        break;
      default:
        return false;
    }
    return true;
  }
}

?>