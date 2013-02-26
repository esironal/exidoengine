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
 * Session database class.
 * @package    core
 * @subpackage session
 * @copyright  Sharapov A.
 * @created    22/04/2010
 * @version    1.0
 */
final class Session_Db extends Db
{
  public $db_table;
  public $db;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   */
  public function __construct()
  {
    // TODO : fix db objects with names
    parent::__construct();
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets a session table name.
   * @param string $table
   * @return void
   */
  public function setDbTableName($table)
  {
    $this->db_table = $table;
  }

  // ---------------------------------------------------------------------------

  /**
   * Puts the session data into the database.
   * @param array $data
   * @param string $sessiondata serialized array
   * @return bool
   */
  public function setData(array $data, $sessiondata)
  {
    if(empty($this->db_table)) {
      return false;
    }
    $sql = "INSERT INTO `".$this->db_table."` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `data`) ";
    $sql.= "VALUES ('".$data['session_id']."', '".$data['ip_address']."', '".$data['user_agent']."', '".$data['last_activity']."', '".$sessiondata."')";
    return $this->db->exec($sql);
  }

  // ---------------------------------------------------------------------------

  /**
   * Removes a session data from database.
   * @param string $session_id
   * @return bool
   */
  public function deleteData($session_id)
  {
    if(empty($this->db_table)) {
      return false;
    }
    $sql = "DELETE FROM `".$this->db_table."` ";
    $sql.= "WHERE ";
    $sql.= "`session_id`='".$session_id."' ";
    return $this->db->exec($sql);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets session data from database.
   * @param string $session_id
   * @param string $ip_address
   * @param string $useragent
   * @return bool
   */
  public function getUserData($session_id, $ip_address, $useragent)
  {
    if(empty($this->db_table)) {
      return false;
    }
    $sql = "SELECT `session_id`, `ip_address`, `user_agent`, `last_activity`, `data` FROM `".$this->db_table."` ";
    $sql.= "WHERE ";
    $sql.= "`session_id`='".$session_id."' AND ";
    $sql.= "`ip_address`='".$ip_address."' AND ";
    $sql.= "`user_agent`='".$useragent."'";
    $r = $this->db->execNoCache($sql);
    if($r and $r->getNumRows() > 0) {
      return $r->row('array');
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Updates a session activity time.
   * @param int $last_activity
   * @param string $session_id
   * @return bool
   */
  public function updateActivityTime($last_activity, $session_id)
  {
    if(empty($this->db_table)) {
      return false;
    }
    $sql = "UPDATE `".$this->db_table."` ";
    $sql.= "SET ";
    $sql.= "`last_activity`='".$last_activity."' ";
    $sql.= "WHERE ";
    $sql.= "`session_id`='".$session_id."'";
    return $this->db->exec($sql);
  }

  // ---------------------------------------------------------------------------

  /**
   * Updates a user session data.
   * @param string $session_id
   * @param int $last_activity
   * @param string $sessiondata serialized array
   * @return bool
   */
  public function updateUserData($session_id, $last_activity, $sessiondata)
  {
    if(empty($this->db_table)) {
      return false;
    }
    $sql = "UPDATE `".$this->db_table."` ";
    $sql.= "SET ";
    $sql.= "`data`='".$sessiondata."', ";
    $sql.= "`last_activity`='".$last_activity."' ";
    $sql.= "WHERE ";
    $sql.= "`session_id`='".$session_id."'";
    return $this->db->exec($sql);
  }
}

?>