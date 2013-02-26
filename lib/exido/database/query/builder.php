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

include_once 'database/query/builder/abstract.php';
include_once 'database/query/builder/service.php';
include_once 'database/query/builder/select.php';
include_once 'database/query/builder/update.php';
include_once 'database/query/builder/insert.php';
include_once 'database/query/builder/delete.php';
include_once 'database/query/builder/custom.php';

/**
 * Database query builder main class.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2012
 * @version    1.0
 */
final class Database_Query_Builder
{
  public $config_scope = 'default';
  public $config       = null;

  // ---------------------------------------------------------------------------

  /**
   * Set a configuration scope for a custom database. Means that doesn't set in
   * the app/config/database.php file.
   * @param string $name
   * @param array $config
   * @return void
   */
  public function setConfigScope($name, $config = null)
  {
    if( ! is_array($config))
      $config = array();
    $this->config = $config;
    $this->config_scope = $name;
  }

  // ---------------------------------------------------------------------------

  /**
   * Init an UPDATE builder.
   * @param string $table
   * @param array $aData
   * @return bool|object
   */
  public function update($table, $aData)
  {
    $db = new Database_Query_Builder_Update($table, $this->config_scope, $this->config);
    $db->setFields($aData);
    return $db;
  }

  // ---------------------------------------------------------------------------

  /**
   * Init a SELECT builder.
   * @param string $table
   * @param string|array $aData
   * @return bool|object
   */
  public function select($table, $aData = '*')
  {
    $db = new Database_Query_Builder_Select($table, $this->config_scope, $this->config);
    $db->setFields($aData);
    return $db;
  }

  // ---------------------------------------------------------------------------

  /**
   * Init an INSERT builder.
   * @param string $table
   * @param array $aData
   * @return bool|object
   */
  public function insert($table, $aData)
  {
    $db = new Database_Query_Builder_Insert($table, false, $this->config_scope, $this->config);
    $db->setInsertFields($aData);
    return $db;
  }

  // ---------------------------------------------------------------------------

  /**
   * Init an REPLACE builder.
   * @param string $table
   * @param array $aData
   * @return bool|object
   */
  public function replace($table, $aData)
  {
    $db = new Database_Query_Builder_Insert($table, true, $this->config_scope, $this->config);
    $db->setInsertFields($aData);
    return $db;
  }

  // ---------------------------------------------------------------------------

  /**
   * Init a DELETE builder.
   * @param string $table
   * @return bool|object
   */
  public function delete($table)
  {
    return new Database_Query_Builder_Delete($table, $this->config_scope, $this->config);
  }

  // ---------------------------------------------------------------------------

  /**
   * Executes a custom query.
   * @param string $sql
   * @param array $values
   * @return bool|object
   */
  public function query($sql, array $values = array())
  {
    $db = new Database_Query_Builder_Custom($this->config_scope, $this->config);
    $db->setQuery($sql, $values);
    return $db;
  }

  // ---------------------------------------------------------------------------

  /**
   * Executes SHOW TABLES query.
   * @param string $pattern
   * @return bool|object
   */
  public function tables($pattern = null)
  {
    $db = new Database_Query_Builder_Service($this->config_scope, $this->config);
    $db->setShowTableQuery($pattern);
    return $db;
  }
}

?>