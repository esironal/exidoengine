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
 * Abstract model class
 * @package    core
 * @subpackage model
 * @copyright  Sharapov A.
 * @created    01/09/2012
 * @version    1.0
 */
abstract class Model_Db_Eav_Abstract extends Model_Mapper
{
  /**
   * Db instance
   * @var
   */
  public $db;

  /**
   * Entity-Attribute-Value instance
   * @var
   */
  public $eav_instance;

  /**
   * Model_Registry object
   * @var
   */
  protected $_aData;

  /**
   * Data types array
   * @var
   */
  protected $_data_types = null;

  /**
   * Error list
   * @var
   */
  protected $_errors = null;

  /**
   * Table masks
   * @var
   */
  protected $_table_masks = array(
    'attribute'     => '%f_attribute',
    'attribute_set' => '%f_attribute_set',
    'attribute_set_list' => '%f_attribute_set_list',
    'attribute_value'    => '%f_attribute_value_%t'
  );

  // ---------------------------------------------------------------------------

  /**
   * Loads URI and Input objects into the controller.
   * Check if the requested environment the system does supports.
   */
  function __construct($instance)
  {
    $this->db           = Registry::factory('Database_Query_Builder');
    $this->_data_types  = $this->_getDataTypes();
    $this->eav_instance = $this->_checkInstance($instance);
    $this->_aData       = Model_Registry::instance(true);
  }

  // ---------------------------------------------------------------------------

  /**
   * Get list of data types.
   * @return mixed
   */
  private function _getDataTypes()
  {
    return $this->db->select('data_type', array('data_type_key', 'data_type_table as data_type'))
      ->limit()->exec()->resultToAssoc('data_type_key', 'data_type');
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if all the tables are exists.
   * @param string $instance
   * @return string
   * @throws Exception_Exido
   */
  private function _checkInstance($instance)
  {
    if( ! $r = $this->db->tables($instance)->exec()->result()) {
      throw new Exception_Exido('EAV instance %s is broken. Please reinstall the system', array($instance));
    }

    $tables = array();
    foreach($r as $obj) {
      if($t = end($obj)) $tables[] = $t;
    }

    foreach($this->_data_types as $data_type) {
      $this->_table_masks['attribute_value_'.$data_type] =
        str_replace('%t', $data_type, $this->_table_masks['attribute_value']);
    }
    unset($this->_table_masks['attribute_value']);

    // Check tables
    foreach($this->_table_masks as $table) {
      $table = str_replace('%f', $instance, $table);
      if( ! in_array($table, $tables)) {
        throw new Exception_Exido('EAV instance %s is broken. Please reinstall the system', array($instance));
      }
    }
    return $instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * Resort attributes.
   * @param array $attributes
   * @return array
   */
  protected function _sortAttributes($attributes)
  {
    $output = array();
    foreach($attributes as $f) {
      $output[$f->attribute_key] = $f;
    }
    return $output;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get attribute value.
   * @param array $attributes
   * @return array
   */
  protected function _getValue($attributes)
  {
    return $this->db->select($this->eav_instance.'_attribute_value_'.$attributes->data_type_table, array('value', 'value_id'))
      ->where(array('attribute_id' => $attributes->attribute_id, 'entity_id' => $attributes->entity_id))
      ->exec()
      ->row();
  }

  // ---------------------------------------------------------------------------

  /**
   * Set error.
   * @param string $key
   * @param string $text
   * @return void
   */
  protected function _setError($key, $text)
  {
    $this->_errors[$key] = $text;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get errors.
   * @return array
   */
  public function getErrors()
  {
    return $this->_errors;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get errors in string.
   * @param string $delimiter
   * @return array
   */
  public function getErrorString($delimiter = ", ")
  {
    return implode($delimiter, $this->getErrors());
  }

  // ---------------------------------------------------------------------------

  /**
   * Get error.
   * @param string $key
   * @return string
   */
  public function getError($key)
  {
    return (isset($this->_errors[$key])) ? $this->_errors[$key] : false;
  }
}

?>