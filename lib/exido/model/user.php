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
 * User model class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    14/06/2010
 * @version    1.0
 */
final class Model_User extends Model_Db_Abstract
{
  /**
   * User object
   * @var
   */
  private $_user;

  // ---------------------------------------------------------------------------

  /**
   * Get user. Return FALSE if the user does not found.
   * @param int $user_id
   * @return mixed
   */
  public function getUser($user_id)
  {
    if($r = $this->db->select('user', '*')->where(array('user_id' => $user_id))
            ->limit(1)->exec()->row()) {
      return Registry::factory('Model_Auth_User', $r);
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get user. Return FALSE if the user does not found.
   * @return mixed
   */
  public function getCurrentUser()
  {
    return $this->_user;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get user. Return FALSE if the user does not found.
   * @param string $name
   * @param string $password
   * @return mixed
   */
  public function getUserSessionId($name, $password)
  {
    if($r = $this->db->select('user', 'unique_session_id')->where(array('user_name' => $name, 'password' => $password))
      ->limit(1)->exec()->row()) {
      return $r->unique_session_id;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get user. Return FALSE if the user is not found.
   * @param string $key
   * @return mixed
   */
  public function getUserByUniqueKey($key)
  {
    if($r = $this->db->select('user', '*')->where(array('unique_session_id' => $key))
      ->limit(1)->exec()->row()) {
      return $this->_user = Registry::factory('Model_Auth_User', $r);
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get roles list.
   * @return mixed
   */
  public function getRoleList()
  {
    return $this->db->select('user_role', array('role_key', 'description'))
      ->orderDesc('position')
      ->order('role_key')
      ->exec()
      ->resultToAssoc('role_key', 'description');
  }

  // ---------------------------------------------------------------------------

  /**
   * Get user. Return FALSE if the user does not found.
   * @param int $user_id
   * @param string $instance
   * @return mixed
   */
  public function getUserAccess($user_id, $instance)
  {
    if($r = $this->db->select('user_access', array('component','permissions'))->where(array('user_id' => $user_id, 'instance' => $instance))
      ->limit(1)->exec()->resultToAssoc('component', 'permissions')) {
      $this->_clearData();
      return $r;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Add user. Return autoincrement value if the user has been successfully created.
   * @return mixed
   */
  public function addUser()
  {
    if($r = $this->db->insert('user', $this->_getData())->exec(1)) {
      $this->_clearData();
      return $r->getInsertId();
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Update user.
   * @param int $user_id
   * @return bool
   */
  public function updateUser($user_id)
  {
    return $this->db->update('user', $this->_getData())
      ->where(array('user_id' => $user_id))
      ->exec();
  }

  // ---------------------------------------------------------------------------

  /**
   * Get list of users.
   * @return mixed
   */
  public function getUserList()
  {
    return $this->db->select('user', '*')
      //->where()
      ->limit()->order('user_id')->exec()->result();
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if user name is unique. Returns TRUE if it is.
   * @param array $user_name
   * @return bool
   */
  public function checkIfUsernameIsUnique($user_name)
  {
    if($r = $this->db->select('user', 'user_id')
      ->where(array('user_name' => $user_name))
      ->exec()) {
      return ($r->getNumRows() == 0) ? true : false;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if user email is unique. Returns TRUE if it is.
   * @param array $user_email
   * @return bool
   */
  public function checkIfEmailIsUnique($user_email)
  {
    if($r = $this->db->select('user', 'user_id')
      ->where(array('user_email' => $user_email))
      ->exec()) {
      return ($r->getNumRows() == 0) ? true : false;
    }
    return false;
  }
}

?>