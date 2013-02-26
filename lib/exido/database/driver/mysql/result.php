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
 * Result class.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2010
 * @version    1.1
 */
final class Database_Driver_Mysql_Result extends Database_Result
{
  /**
   * Gets number of affected rows.
   * @return int
   */
  public function getNumRows()
  {
    return @mysql_num_rows($this->result_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets number of affected fields.
   * @return int
   */
  public function getNumFields()
  {
    return @mysql_num_fields($this->result_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Free memory.
   * @return void
   */
  public function freeResult()
  {
    if(is_resource($this->result_id)) {
      @mysql_free_result($this->result_id);
      $this->result_id = false;
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Fetches the result as associated array.
   * @return array
   */
  protected function _fetchAssoc()
  {
    return @mysql_fetch_assoc($this->result_id);
  }

  // ---------------------------------------------------------------------------

  /**
   * Fetches the result as an array of objects.
   * @return object|stdClass
   */
  protected function _fetchObject()
  {
    return @mysql_fetch_object($this->result_id, 'Database_Mapper_Result');
  }
}

?>