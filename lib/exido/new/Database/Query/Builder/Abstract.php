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
 * Abstract database query builder.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2012
 * @version    1.0
 */
abstract class Exido_Database_Query_Builder_Abstract extends Database
{
  protected $_table;
  protected $_fields;
  protected $_keys;
  protected $_limit;

  // ---------------------------------------------------------------------------

  /**
   * Executes a query.
   * @param bool $print_last
   * @return mixed
   */
  public function exec($print_last = false)
  {
    if($print_last) {
      $this->_printSql();
    }
    // Execute an SQL
    $result = $this->db->exec($this->_query());
    if( ! $result) {
      return Registry::factory('Database_Mapper_Result_Dummy');
    }
    // Clear last SQL
    //$this->_clear();
    return $result;
  }

  // ---------------------------------------------------------------------------

  /**
   * Escape quotes.
   * @param string $value
   * @return string
   */
  public function escape($value)
  {
    return $this->db->prepareString($value);
  }

  // ---------------------------------------------------------------------------

  /**
   * Where construction - "And" version.
   * @param array $keys
   * @param bool $is_or if we want to use "OR" version
   * @return Exido_Database_Query_Builder_Abstract
   */
  public function where(array $keys, $is_or = false)
  {
    if($is_or) $c = 'OR'; else $c = 'AND';
    foreach($keys as $key => $value) {
      $value = trim($value);
      if(preg_match('/^\{(=|!=|<|>|<=|>=|like|not\slike)\}(.*)/i', $value, $m)) {
        $cond = trim($m[1]);
        $val  = trim($m[2]);
        $this->_keys[$c][$key] = "`".$key."` ".$cond." '".$this->db->prepareString($val)."'";
      } elseif(strtolower($value) == 'null' or $value == null) {
        $this->_keys[$c][$key] = "`".$key."` IS NULL";
      } elseif(strtolower($value) == 'not null') {
        $this->_keys[$c][$key] = "`".$key."` IS NOT NULL";
      } else {
        $this->_keys[$c][$key] = "`".$key."`='".$this->db->prepareString($value)."'";
      }
    }
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Where construction - "NULL" version.
   * @param array $keys
   * @return Exido_Database_Query_Builder_Abstract
   *
  public function whereNull($keys)
  {
    if( ! is_array($keys))
      $keys = array($keys);
    foreach($keys as $key)
      $this->_keys[$key] = "`".$key."` IS NULL";
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Where construction - "IS NOT NULL" version.
   * @param mixed $keys
   * @return Exido_Database_Query_Builder_Abstract
   *
  public function whereNotNull($keys)
  {
    if( ! is_array($keys))
      $keys = array($keys);
    foreach($keys as $key)
      $this->_keys[$key] = "`".$key."` IS NOT NULL";
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Where construction - "And" version.
   * @param array $keys
   * @return Exido_Database_Query_Builder_Abstract
   */
  public function whereAnd(array $keys)
  {
    return $this->where($keys, false);
  }

  // ---------------------------------------------------------------------------

  /**
   * Where construction - "Or" version.
   * @param array $keys
   * @return Exido_Database_Query_Builder_Abstract
   */
  public function whereOr(array $keys)
  {
    return $this->where($keys, true);
  }

  // ---------------------------------------------------------------------------

  /**
   * Limit construction.
   * @param int $limit
   * @param int $offset
   * @return Exido_Database_Query_Builder_Abstract
   */
  public function limit($limit = 10000, $offset = 0)
  {
    if($limit > 0) {
      if($offset > 0)
        $offset = $offset.', ';
      else
        $offset = '';
      $this->_limit = $offset.$limit;
    }
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Construct WHERE conditions.
   * @return string
   */
  protected function _constructWhere()
  {
    $sql = '';
    // If where condition exists
    if( ! empty($this->_keys)) {
      $sql = " WHERE";
      if(isset($this->_keys['OR']))
        $sql.= ' ('.implode(' OR ', $this->_keys['OR']).')';
      if(isset($this->_keys['AND'])) {
        if(isset($this->_keys['OR']))
          $sql.= ' AND ';
        $sql.= implode(' AND ', $this->_keys['AND']);
      }
    }
    return $sql;
  }

  // ---------------------------------------------------------------------------

  /**
   * Construct LIMIT conditions.
   * @return string
   */
  protected function _constructLimit()
  {
    $sql = '';
    if( ! empty($this->_limit))
      $sql.= " LIMIT ".$this->_limit;
    return $sql;
  }

  // ---------------------------------------------------------------------------

  /**
   * Construct ORDER BY conditions.
   * @return string
   */
  protected function _constructOrderby()
  {
    $sql = '';
    if( ! empty($this->_order_by)
      and (isset($this->_order_by['ASC'])
        or isset($this->_order_by['DESC']))
    ) {
      foreach($this->_order_by as $order => $field)
        $sql.= ", `".implode('` '.$order.', `', $this->_order_by[$order])."` ".$order;
      $sql = " ORDER BY".ltrim($sql, ',');
    }
    return $sql;
  }

  // ---------------------------------------------------------------------------

  /**
   * Print SQL construction.
   * @return void
   */
  protected function _printSql()
  {
    print $this->_query().'<br />';
  }

  // ---------------------------------------------------------------------------

  /**
   * Clear an SQL construction.
   * @return void
   */
  protected function _clear()
  {
    $this->_table = $this->_fields = $this->_keys = $this->_limit = null;
  }
}

?>