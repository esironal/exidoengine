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
include_once 'database/cache/read.php';

/**
 * Database driver class.
 * This is the platform-independent base DB implementation class.
 * This class will not be called directly. Rather, the adapter
 * class for the specific database will extend and instantiate it.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2010
 * @version    1.0
 */
abstract class Database_Adapter implements Database_Interface_Adapter//, Database_Interface_QueryBuiler
{
  public $type;
  public $pconnect;
  public $character_set;
  public $db_collation;
  public $lc_time_names;
  public $time_zone;
  public $table_prefix    = '';
  public $benchmark       = true;
  public $save_queries    = true;
  public $unavailable_die = true;
  public $queries         = array();
  public $bm_times        = array();
  public $query_count     = 0;
  public $cache_enabled   = false;
  public $cache_lifetime  = 3600;
  public $cache_folder    = '';
  public $is_last_write   = false;
  public $print_last_sql  = false;
  public $last_sql;
  public $user;
  public $pass;
  public $host;
  public $port;
  public $socket;
  public $database;
  public $timeout;
  public $conn_id   = null;
  public $result_id = null;

  private $_bm_decimals = 4;

  // ---------------------------------------------------------------------------

  /**
   * Constructor. Accept one parameter containing the database connection settings.
   * @param array $config
   */
  public function __construct(array $config)
  {
    foreach($config as $k => $v) {
      $this->$k = $v;
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Executes the query without caching.
   * @param string $sql
   * @return bool
   */
  public function execNoCache($sql)
  {
    return $this->exec($sql, null, true);
  }

  // ---------------------------------------------------------------------------

  /**
   * Executes the query.
   * Accept an SQL string as input and return a result object upon
   * successful execution of a "read" type query. Return boolean TRUE
   * upon successful execution of a "write" type query. Return boolean
   * FALSE upon failure.
   * @param string $sql
   * @param null $cache_lifetime
   * @param bool $no_cache
   * @return bool
   * @throws Exception_Exido
   * @throws Exception_Database
   */
  public function exec($sql, $cache_lifetime = null, $no_cache = false)
  {
    if($sql == '') {
      return false;
    }
    // Set the last SQL query
    $this->last_sql = $sql;
    unset($sql);

    // Connect to the database and set the connection ID
    if( ! is_resource($this->conn_id)) {
      // Set custom timeout
      if($this->timeout) {
        @ini_set('mysql.connect_timeout', $this->timeout);
      }
      // Connect to the database
      $this->conn_id = ($this->pconnect) ? $this->pconnect() : $this->connect();

      if( ! $this->conn_id) {
        // If we can't go next without a database instance
        if($this->unavailable_die) {
          throw new Exception_Exido('Unable to connect to database server %s', array($this->host));
        } else return false;
      }

      // Select the DB... assuming a database name is specified in the config file
      if( ! $this->selectDatabase()) {
        if($this->unavailable_die) {
          throw new Exception_Exido('Unable to select database %s', array($this->database));
        } else return false;
      } else {
        // Set a connection charset
        if( ! $this->setCharset($this->character_set, $this->db_collation))
          return false;
        // Set time names
        if( ! $this->setTimeNames($this->lc_time_names))
          return false;
        // Set time zone
        if( ! empty($this->time_zone))
          $this->setTimeZone($this->time_zone);
      }
    }

    // No connection resource?  Throw an error
    if( ! $this->conn_id)
      return false;
    // Save the  query for debugging
    if($this->save_queries == true)
      $this->queries[] = $this->last_sql;

    // Start benchmarking
    if($this->benchmark)
      $time_start = microtime(true);
    // If the query caching is enabled
    if($this->cache_enabled) {
      // Set the parameters
      if($cache_lifetime != 0)
        $this->cache_lifetime = $cache_lifetime;
      $cache = new Database_Cache_Read($this);
    }

    // The cache result if FALSE by default
    $cache_result = false;

    // Check cache for cached query
    if($no_cache or ! $this->cache_enabled or ! $cache_result = $cache->getCache()) {
      // Execute a query
      if(false === ($this->result_id = $this->_execSimpleQuery($this->last_sql)))
        // Throw an error if the query has been failed
        throw new Exception_Database($this);
    }

    // Stop benchmarking and calculate the results
    if($this->benchmark) {
      $time = 0;
      $time_end = microtime(true);
      $time += $time_end - $time_start;
      $time = number_format($time, $this->_bm_decimals);
      $this->bm_times[] = array('sql' => $this->last_sql, 'execution_time' => $time);
    }

    // Increment the query counter
    $this->query_count++;

    // Was the query a "write" type?
    // If so we'll simply return true
    if($this->_isWriteType($this->last_sql) === true) {
      $this->is_last_write = true;
      return $this;
    }
    // Load and instantiate the result driver
    include_once 'database/driver/'.$this->type.'/result.php';
    $driver            = 'Database_Driver_'.ucfirst($this->type).'_Result';
    $result            = new $driver();
    $result->conn_id   = $this->conn_id;
    $result->result_id = $this->result_id;
    $result->last_sql  = $this->last_sql;
    $result->cache_enabled  = (($no_cache) ? false : $this->cache_enabled);
    $result->cache_folder   = $this->cache_folder;
    $result->cache_lifetime = $this->cache_lifetime;
    $result->cache_data     = $cache_result;
    return $result;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a query count.
   * @return int
   */
  public function getQueryCount()
  {
    return $this->query_count;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a query list.
   * @return array
   */
  public function getQueryList()
  {
    return $this->queries;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a last executed query.
   * @return string
   */
  public function getLastQuery()
  {
    return $this->last_sql;
  }

  // ---------------------------------------------------------------------------

  /**
   * Is the last query has a write type.
   * @return bool
   */
  public function isLastQueryWrite()
  {
    return $this->is_last_write;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a benchmark results.
   * @return array
   */
  public function getBenchmark()
  {
    if($this->benchmark == false)
      return array();
    return $this->bm_times;
  }

  // ---------------------------------------------------------------------------

  /**
   * Determines if a query is a "write" type.
   * @return bool
   */
  private function _isWriteType()
  {
    return (bool)preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $this->last_sql);
  }
}

?>