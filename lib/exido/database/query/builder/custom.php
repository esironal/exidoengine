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
 * Database query builder - Custom queries.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2012
 * @version    1.0
 */
final class Database_Query_Builder_Custom extends Database_Query_Builder_Abstract
{
  protected $_sql;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param string $db
   * @param string $config
   */
  public function __construct($db = null, $config = null)
  {
    parent::__construct($db, $config);
  }

  // ---------------------------------------------------------------------------

  /**
   * Set custom query.
   * @param string $sql
   * @param array $values
   * @return Database_Query_Builder_Select
   */
  public function setQuery($sql, array $values = array())
  {
    // Escape quotes for the custom query
    $this->_sql = $this->_quote($sql, $values);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Generates an SQL.
   * @return string
   */
  protected function _query()
  {
    return $this->_sql;
  }

  // ---------------------------------------------------------------------------

  /**
   * Replace the entities with quoted values.
   * @param string $sql
   * @param array $values
   * @return string
   */
  private function _quote($sql, array $values)
  {
    foreach($values as $key => $value)
      $sql = str_replace($key, $this->db->prepareString($value), $sql);
    return $sql;
  }
}

?>