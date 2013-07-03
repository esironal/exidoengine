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

include_once 'database/query/builder/update/abstract.php';

/**
 * Database query builder - Update version.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2012
 * @version    1.0
 */
final class Database_Query_Builder_Update extends Database_Query_Builder_Update_Abstract
{
  protected $_table;
  protected $_fields;
  protected $_keys;
  protected $_limit;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param string $table
   * @param string $db
   * @param string $config
   */
  public function __construct($table, $db = null, $config = null)
  {
    parent::__construct($db, $config);
    $this->_table = $table;
  }

  // ---------------------------------------------------------------------------

  /**
   * Set fields.
   * @param array $fields
   * @return Database_Query_Builder_Update
   */
  public function setFields(array $fields)
  {
    foreach($fields as $key => $value) {
      if($value === null)
        $this->_fields[$key] = "`".$key."` = NULL";
      elseif(strtolower($value) == 'now()')
        $this->_fields[$key] = "`".$key."` = NOW()";
      else
        $this->_fields[$key] = "`".$key."` = '".$this->db->prepareString($value)."'";
    }
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Generates an SQL.
   * @return string
   */
  protected function _query()
  {
    if(empty($this->_fields))
      return '';
    // Generating a SELECT query
    $sql = "UPDATE `".$this->_table."` SET ";
    $sql.= implode(', ', $this->_fields);
    // Construct WHERE conditions
    $sql.= $this->_constructWhere();
    // Limit
    $sql.= $this->_constructLimit();
    return $sql;
  }
}

?>