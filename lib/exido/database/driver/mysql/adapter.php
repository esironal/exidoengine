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
 * MySQL driver.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2010
 * @version    1.0
 */
final class Database_Driver_Mysql_Adapter extends Database_Adapter
{
  /**
   * Connects to MySQL server.
   * @return resource
   */
  public function connect()
  {
    if($this->port != '') {
      $this->host.= ':'.$this->port;
    }
    return @mysql_connect($this->host, $this->user, $this->pass, true);
  }

  // ---------------------------------------------------------------------------

  /**
   * Creates a persistent connection to MySQL server.
   * @return resource
   */
  public function pconnect()
  {
    if($this->port != '') {
      $this->host.= ':'.$this->port;
    }
    return @mysql_pconnect($this->host, $this->user, $this->pass);
  }

  // ---------------------------------------------------------------------------

  /**
   * Ping server.
   *
   * @return  void
   */
  public function ping()
  {
    if(@mysql_ping($this->conn_id) === false) {
      $this->conn_id = false;
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Selects a database.
   * @return bool
   */
  public function selectDatabase()
  {
    return @mysql_select_db($this->database, $this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets the connection charset.
   * @param string $charset
   * @param string $collation
   * @return resource
   */
  public function setCharset($charset, $collation)
  {
    return @mysql_query("SET NAMES '".$charset."' COLLATE '".$collation."'", $this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets locale.
   * @param string $locale
   * @return resource
   */
  public function setTimeNames($locale)
  {
    return @mysql_query("SET lc_time_names = '".$locale."'", $this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets a timezone.
   * @param string $timezone
   * @return resource
   */
  public function setTimeZone($timezone)
  {
    return @mysql_query("SET time_zone = '".$timezone."'", $this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Get show tables query.
   * @param string $pattern
   * @return string
   */
  public function getShowTablesQuery($pattern = null)
  {
    return "SHOW TABLES".(( ! empty($pattern)?" LIKE '%".$pattern."%'" : ""));
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an affected rows.
   * @return int
   */
  public function getAffectedRows()
  {
    return @mysql_affected_rows($this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an auto-generated insert ID.
   * @return int
   */
  public function getInsertId()
  {
    return @mysql_insert_id($this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an error text.
   * @return string
   */
  public function getErrorText()
  {
    return @mysql_error($this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an error code.
   * @return int
   */
  public function getErrorNo()
  {
    return @mysql_errno($this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the server information.
   * @return string
   */
  public function getServerVersion()
  {
    return @mysql_get_server_info($this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the connection information.
   * @return string
   */
  public function getHostInfo()
  {
    return @mysql_get_host_info($this->conn_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the connection ID.
   * @return resource
   */
  public function getId()
  {
    return $this->conn_id;
  }

  // ---------------------------------------------------------------------------

  /**
   * Closes the connection.
   * @param resource $conn_id
   * @return void
   */
  public function close($conn_id)
  {
    if(is_resource($conn_id) or is_object($conn_id)) {
      @mysql_close($conn_id);
    }
    $this->_conn_id = false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Escapes a special characters.
   * @param string $str
   * @param bool $like
   * @return array|mixed|string
   */
  public function prepareString($str, $like = false)
  {
    if(is_array($str)) {
      foreach($str as $key => $val) {
        $str[$key] = $this->prepareString($val, $like);
      }
      return $str;
    }

    if(function_exists('mysql_real_escape_string') and is_resource($this->conn_id)) {
      $str = mysql_real_escape_string($str, $this->conn_id);
    } else {
      $str = addslashes($str);
    }
    // Escape a "like query" characters.
    if($like === true) {
      $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
    }
    return $str;
  }

  // ---------------------------------------------------------------------------

  /**
   * Executes a simple query.
   * @param string $sql
   * @return resource
   */
  protected function _execSimpleQuery($sql)
  {
    return @mysql_query($sql, $this->conn_id);
  }
}

?>