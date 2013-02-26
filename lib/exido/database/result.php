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

include_once 'database/cache.php';
include_once 'database/cache/write.php';

/**
 * Database result class
 * This is the platform-independent result class.
 * This class will not be called directly. Rather, the adapter
 * class for the specific database will extend and instantiate it.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    07/02/2010
 * @version    1.1
 */
abstract class Database_Result implements Database_Interface_Result
{
  public $conn_id   = null;
  public $result_id = null;
  public $last_sql  = '';
  public $cache_enabled  = true;
  public $cache_lifetime = 3600;
  public $cache_folder   = '';
  public $cache_data     = array();

  public $result_array   = array();
  public $result_object  = array();
  public $current_row    = 0;

  // ---------------------------------------------------------------------------

  /**
   * Query result. Act as a wrapper function for the following functions.
   * @return array
   */
  public function resultArray()
  {
    return $this->result('array');
  }

  // ---------------------------------------------------------------------------

  /**
   * Query result. Act as a wrapper function for the following functions.
   * @param string $type
   * @return array
   */
  public function result($type = 'object')
  {
    if($this->cache_data != false) {
      return ($type == 'object') ? $this->_getObjectFromCache() : $this->_getArrayFromCache();
    }
    return ($type == 'object') ? $this->_getObject() : $this->_getArray();
  }

  // ---------------------------------------------------------------------------

  /**
   * Makes an associative array from the result array,
   * where the key is $needle_key and the value is $needle_value
   * @param string $needle_key
   * @param string $needle_value
   * @return bool|array
   */
  public function resultToAssoc($needle_key, $needle_value)
  {
    $output = array();
    $i      = 0;
    foreach($this->resultArray() as $d) {
      if($needle_key == null) {
        $output[$i] = $d[$needle_value];
      } else {
        if(isset($d[$needle_key]) and isset($d[$needle_value])) {
          $output[$d[$needle_key]] = $d[$needle_value];
        }
      }
      $i++;
    }
    return empty($output) ? false : $output;
  }

  // ---------------------------------------------------------------------------

  /**
   * Query result. Act as a wrapper function for the following functions.
   * @return array
   */
  public function rowArray()
  {
    return $this->row('array');
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a one result row.
   * @param string $type
   * @return bool|array
   */
  public function row($type = 'object')
  {
    if($this->cache_data != false) {
      return ($type == 'object') ? $this->_fetchObjectFromCache() : $this->_fetchArrayFromCache();
    }
    if($this->result_id === false or $this->getNumRows() == 0) {
      return false;
    }
    if($type == 'object') {
      return $this->_fetchObject();
    }
    return $this->_fetchAssoc();
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a first result row.
   * @param string $type
   * @return array
   */
  public function getFirstRow($type = 'object')
  {
    $result = $this->result($type);
    if(count($result) == 0) {
      return $result;
    }
    return $result[0];
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a last result row.
   * @param string $type
   * @return array
   */
  public function getLastRow($type = 'object')
  {
    $result = $this->result($type);
    if(count($result) == 0) {
      return $result;
    }
    return $result[count($result) - 1];
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a next result row.
   * @param string $type
   * @return array|bool
   */
  public function getNextRow($type = 'object')
  {
    $result = $this->result($type);
    if(count($result) == 0) {
      return $result;
    }
    if( ! isset($result[$this->current_row + 1])) {
      return false;
    }
    $this->current_row++;
    return $result[$this->current_row];
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a previous result row.
   * @param string $type
   * @return array|bool
   */
  public function getPreviousRow($type = 'object')
  {
    $result = $this->result($type);
    if(count($result) == 0) {
      return $result;
    }
    if( ! isset($result[$this->current_row - 1])) {
      return false;
    }
    $this->current_row--;
    return $result[$this->current_row];
  }

  // ---------------------------------------------------------------------------

  /**
   * Query result. "object" version.
   * @return array
   */
  private function _getObject()
  {
    if(count($this->result_object) > 0) {
      return $this->result_object;
    }
    if($this->result_id === false or $this->getNumRows() == 0) {
      return false;
    }
    while($row = $this->_fetchObject()) {
      $this->result_object[] = $row;
    }
    // If cache enabled
    if($this->cache_enabled) {
      // Initialize the cache class
      $cache = new Database_Cache_Write($this);
      // Write cache
      $cache->setCache();
    }
    return $this->result_object;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a query result from cache. "object" version.
   * @return array
   */
  private function _getObjectFromCache()
  {
    return $this->cache_data;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a query result from cache. "array" version.
   * @return array
   */
  private function _fetchObjectFromCache()
  {
    return reset($this->cache_data);
  }

  // ---------------------------------------------------------------------------

  /**
   * Query result. "array" version.
   * @return array
   */
  private function _getArray()
  {
    if(count($this->result_array) > 0) {
      return $this->result_array;
    }
    if($this->result_id === false or $this->getNumRows() == 0) {
      return false;
    }
    while($row = $this->_fetchAssoc()) {
      $this->result_array[] = $row;
    }
    // If cache enabled
    if($this->cache_enabled) {
      $cache = new Database_Cache_Write($this);
      // Write cache
      $cache->setCache();
    }
    return $this->result_array;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a query result from cache. "array" version.
   * @return array
   */
  private function _getArrayFromCache()
  {
    $result = array();
    foreach($this->cache_data as $i => $v) {
      $result[$i] = (array)$v;
    }
    return $result;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a query result from cache. "array" version.
   * @return array
   */
  private function _fetchArrayFromCache()
  {
    return (array)reset($this->cache_data);
  }
}

?>