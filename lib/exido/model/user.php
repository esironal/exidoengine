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
   * @param string $name
   * @param string $password
   * @return mixed
   */
  public function getUser($name, $password)
  {
    if($r = $this->db->select('user', '*')->where(array('user_name' => $name, 'password' => $password))
            ->limit(1)->exec()->row()) {
      return $this->_user = Registry::factory('Model_Auth_User', $r);
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
    if($r = $this->db->insert('user', $this->_getData())->exec()) {
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
}

?>