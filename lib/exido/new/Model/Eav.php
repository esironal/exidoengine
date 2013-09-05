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

include_once 'Model/Db/Eav/Abstract.php';
include_once 'Model/Db/abstract.php';

/**
 * Eav model.
 * @package    core
 * @copyright  Sharapov A.
 * @created    14/06/2010
 * @version    1.0
 */
final class Model_Eav extends Model_Db_Eav_Abstract
{
  /**
   * Add entity.
   * @param array $attributes
   * @param int $parent_id
   * @param string $attribute_set
   * @return mixed
   */
  public function addEntity(array $attributes, $parent_id = null, $attribute_set = 'default')
  {
    // Get attribute set
    if( ! $set = $this->getAttributeSet($attribute_set)) {
      // If attribute set doesn't found
      $this->_setError('attribute_set', sprintf(__('Attribute set %s is not found'), $attribute_set));
      return false;
    }

    foreach($set as $key => $f) {
      // Check if the required attribute is exists
      if($f->is_required) {
        // Return FALSE if the required attribute isn't exists and doesn't have a default value
        if( ! isset($attributes[$key]) and empty($f->default_value)) {
          $this->_setError($key, sprintf(__('Attribute %s must not be empty'), $key));
          return false;
        }
      }
      // Check if unique attribute is unique
      if($f->is_unique) {
        if( ! $this->_checkUniqueAttributeValue($key, $attributes[$key], $f->data_type_key)) {
          $this->_setError($key, sprintf(__('Attribute %s must be unique'), $key));
          return false;
        }
      }
    }

    // Create new entity
    $this->setEntity_key($this->_genEntityKey());
    $this->setCreated_at('now()');
    $this->setParent_id($parent_id);
    if( ! $entity_id = $this->_saveNewEntity()) {
      $this->_setError($key, __('There is an error was occurred while saving entity'));
      return false;
    }

    // Create values for attributes
    foreach($set as $key => $f) {
      $value = null;
      if(isset($attributes[$key])) {
        $value = $attributes[$key];
      } else {
        $value = $f->default_value;
      }
      if(substr($value, 0, 1) == '@') {
        if(defined($value)) {
          $value = constant($value);
        } else {
          $this->_setError($key, sprintf(__('Undefined constant %s'), $value));
          return false;
        }
      }
      $this->setValue($value);
      $this->setAttribute_id($f->attribute_id);
      $this->setEntity_id($entity_id);
      if( ! $this->_saveAttributeValue($f->data_type_key)) {
        $this->_setError($key, sprintf(__('There is an error was occurred while saving attribute %s'), $f->attribute_key));
        return false;
      }
    }
    // If all was successful we just return an ID of new entity
    return $entity_id;
  }

  // ---------------------------------------------------------------------------

  /**
   * Edit attribute values.
   * @param int $entity_id
   * @param array $attributes
   * @param string $attribute_set
   * @return mixed
   */
  public function editAttributeValues($entity_id, array $attributes, $attribute_set = 'default')
  {
    // Get attribute set
    if( ! $set = $this->getAttributeSet($attribute_set)) {
      // If attribute set doesn't found
      $this->_setError('attribute_set', sprintf(__('Attribute set %s is not found'), $attribute_set));
      return false;
    }

    foreach($set as $key => $f) {
      // Check if the required attribute exists
      if($f->is_required) {
        if( ! isset($attributes[$key]) and empty($f->default_value)) {
          $this->_setError($key, sprintf(__('Attribute %s must not be empty'), $key));
          return false;
        }
      }
      // Check if unique attribute is unique
      if($f->is_unique) {
        if( ! $this->_checkUniqueAttributeValue($key, $attributes[$key], $f->data_type_key, $entity_id)) {
          $this->_setError($key, sprintf(__('Attribute %s must be unique'), $key));
          return false;
        }
      }
    }

    foreach($set as $key => $f) {
      $value = null;
      if(isset($attributes[$key])) {
        $value = $attributes[$key];
      } else {
        $value = $f->default_value;
      }
      if(substr($value, 0, 1) == '@') {
        if(defined($value)) {
          $value = constant($value);
        } else {
          $this->_setError($key, sprintf(__('Undefined constant %s'), $value));
          return false;
        }
      }
      $this->setValue($value);
      if( ! $this->_updateAttributeValue($entity_id, $f->attribute_id, $f->data_type_key)) {
        $this->_setError($key, sprintf(__('There is an error was occurred while saving attribute %s'), $f->attribute_key));
        return false;
      }
    }
    // If all was successful we just return an ID of new entity
    return $entity_id;
  }

  // ---------------------------------------------------------------------------

  /**
   * Remove entity.
   * @param int $entity_id
   * @param string $attribute_set
   * @return bool
   */
  public function removeEntity($entity_id, $attribute_set = 'default')
  {
    // Get attribute set
    if( ! $set = $this->getAttributeSet($attribute_set)) {
      // If attribute set doesn't found
      $this->_setError('attribute_set', sprintf(__('Attribute set %s is not found'), $attribute_set));
      return false;
    }

    // First, we remove an attribute values
    foreach($set as $key => $f) {
      if( ! $this->_removeAttributeValues($entity_id, $f->attribute_id, $f->data_type_key)) {
        $this->_setError($f->attribute_key, sprintf(__('There is an error was occurred while removing value of attribute %s'), $f->attribute_key));
        return false;
      }
    }

    // Remove entity
    if( ! $this->_removeEntity($entity_id)) {
      $this->_setError($entity_id, sprintf(__('There is an error was occurred while removing entity %s'), $entity_id));
      return false;
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if attribute value is unique. Returns TRUE if atttibute is unique.
   * @param array $attributes
   * @param int $skip_entity_id
   * @return bool
   */
  public function checkIfValueIsUnique(array $attributes, $skip_entity_id = 0)
  {
    // Get attributes
    if( ! $set = $this->getAllAttributes()) {
      $this->_setError('no_attributes', __('No attributes found'));
      return false;
    }

    foreach($attributes as $key => $value) {
      // Check if an attribute exists in chosen attribute set
      if( ! isset($set[$key])) {
        $this->_setError($key, sprintf(__('Attribute %s is not found'), $key));
        return false;
      } else {
        // Check if an attribute is set as unique in chosen attribute set
        if($set[$key]->is_unique == 1) {
          if( ! $this->_checkUniqueAttributeValue($key, $value, $set[$key]->data_type_key, $skip_entity_id)) {
            $this->_setError($key, sprintf(__('Attribute %s must be unique'), $key));
            return false;
          }
        } else {
          $this->_setError($key, sprintf(__('Attribute %s may not be unique'), $key));
          return false;
        }
      }
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Remove entity.
   * @param int $entity_id
   * @return bool
   */
  private function _removeEntity($entity_id)
  {
    return $this->db->delete($this->eav_instance.'_entity')
      ->where(array('entity_id' => $entity_id))
      ->exec();
  }

  // ---------------------------------------------------------------------------

  /**
   * Add attribute value.
   * @param int $entity_id
   * @param int $attribute_id
   * @param string $type_key
   * @return bool
   */
  private function _removeAttributeValues($entity_id, $attribute_id, $type_key)
  {
    return $this->db->delete($this->eav_instance.'_attribute_value_'.$type_key)
      ->where(array('attribute_id' => $attribute_id, 'entity_id' => $entity_id))
      ->exec();
  }

  // ---------------------------------------------------------------------------

  /**
   * Add attribute value.
   * @return mixed  ID of entity or false
   */
  private function _saveNewEntity()
  {
    if($r = $this->db->insert($this->eav_instance.'_entity', $this->_getData())->exec()) {
      $this->_clearData();
      return $r->getInsertId();
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Add attribute value.
   * @param string $type_key
   * @return mixed  ID of value or false
   */
  private function _saveAttributeValue($type_key)
  {
    if($r = $this->db->insert($this->eav_instance.'_attribute_value_'.$type_key, $this->_getData())->exec()) {
      $this->_clearData();
      return $r->getInsertId();
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Update attribute value.
   * @param int $entity_id
   * @param int $attribute_id
   * @param string $type_key
   * @return bool
   */
  private function _updateAttributeValue($entity_id, $attribute_id, $type_key)
  {
    return $this->db->update($this->eav_instance.'_attribute_value_'.$type_key, $this->_getData())
      ->where(array('attribute_id' => $attribute_id, 'entity_id' => $entity_id))
      ->exec();
  }

  // ---------------------------------------------------------------------------

  /**
   * Get entities list. Possible to get all child entities.
   * @param int $parent_id
   * @return mixed
   */
  public function getEntities($parent_id = null)
  {
    if($r = $this->db->select($this->eav_instance.'_entity', '*')
      ->where(array('parent_id' => $parent_id))
      ->exec()
      ->result()
    ) {
      foreach($r as $i => $f) {
        $attributes = $this->_getAllAttributesByEntityId($f->entity_id);
        $r[$i]->attributes = $attributes;
      }
      return $r;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get the list of all entities.
   * @return mixed
   */
  public function getAllEntities()
  {
    if($r = $this->db->select($this->eav_instance.'_entity', '*')->exec()->result()) {
      foreach($r as $i => $f) {
        $r[$i]->attributes = $this->_getAllAttributesByEntityId($f->entity_id);
      }
      return $r;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get entity by ID.
   * @param int $entity_id
   * @return mixed
   */
  public function getEntityById($entity_id)
  {
    if($r = $this->db->select($this->eav_instance.'_entity', '*')
      ->where(array('entity_id' => $entity_id))
      ->exec()
      ->row()
    ) {
      $r->attributes = $this->_getAllAttributesByEntityId($entity_id);
      return $r;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get entity by key.
   * @param string $entity_key
   * @return mixed
   */
  public function getEntityByKey($entity_key)
  {
    if($r = $this->db->select($this->eav_instance.'_entity', '*')
      ->where(array('entity_key' => $entity_key))
      ->exec()
      ->row()
    ) {
      $r->attributes = $this->_getAllAttributesByEntityId($r->entity_id);
      return $r;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get entity id by attribute value.
   * @param string $value
   * @param string $data_type
   * @return mixed
   */
  public function getEntityIdByAttributeValue($value, $data_type)
  {
    if($r = $this->db->select($this->eav_instance.'_entity', '*')
      ->where(array('entity_key' => $entity_key))
      ->exec()
      ->row()
    ) {
      $r->attributes = $this->_getAllAttributesByEntityId($r->entity_id);
      return $r;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get an attribute set.
   * @param string $set
   * @return array
   */
  public function getAttributeSet($set)
  {
    $sql = "SELECT t3.*, t1.`attribute_set_key` FROM ";
    $sql.= "`:instance_attribute_set` as t1,";
    $sql.= "`:instance_attribute_set_list` as t2,";
    $sql.= "`:instance_attribute` as t3,";
    $sql.= "`data_type` as t4";
    $sql.= " WHERE ";
    $sql.= "t1.`attribute_set_key`=':set_name' AND ";
    $sql.= "t1.`attribute_set_key`=t2.`attribute_set_key` AND ";
    $sql.= "t2.`attribute_id`=t3.`attribute_id` ";
    $sql.= " ORDER BY t3.`position` DESC";
    if($r = $this->db->query($sql, array(
      ':set_name' => $set,
      ':instance' => $this->eav_instance
    ))->exec()->result()) {
      return $this->_sortAttributes($r);
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get entity by key.
   * @return mixed
   */
  public function getAllAttributes()
  {
    if($r = $this->db->select($this->eav_instance.'_attribute')
      ->orderDesc('position')
      ->exec()
      ->result()
    ) {
      return $this->_sortAttributes($r);
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get entity attribute by data type and entity id.
   * @param string $data_type
   * @param int $entity_id
   * @return mixed
   */
  private function _getAttributeValuesByEntityId($data_type, $entity_id)
  {
    $sql = "SELECT t1.*, t2.`value_id`, t2.`value`, t2.`entity_id` FROM ";
    $sql.= "`:instance_attribute` as t1,";
    $sql.= "`:instance_attribute_value_:data_type` as t2 WHERE ";
    $sql.= "t2.`entity_id`=':entity_id' AND ";
    $sql.= "t2.`attribute_id`=t1.`attribute_id`";
    $sql.= " ORDER BY t1.`position` DESC";
    if($r = $this->db->query($sql, array(
      ':entity_id' => $entity_id,
      ':data_type' => $data_type,
      ':instance'  => $this->eav_instance
    ))->exec()->result()) {
      return $this->_sortAttributes($r);
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get all entity attributes.
   * @param int $entity_id
   * @return mixed
   */
  private function _getAllAttributesByEntityId($entity_id)
  {
    $attributes = array();
    foreach($this->_data_types as $data_type) {
      if($r = $this->_getAttributeValuesByEntityId($data_type, $entity_id)) {
        $attributes = array_merge($r, $attributes);
      }
    }
    return $attributes;
  }

  // ---------------------------------------------------------------------------

  /**
   * Generate a random entity key. Using when creating an entity.
   * @param int $chars
   * @param int $groups
   * @param string $delimiter
   * @param bool $lowcase
   * @return string
   */
  private function _genEntityKey($chars = 8, $groups = 1, $delimiter = '', $lowcase = true)
  {
    Helper::load('guid');
    return guidGet($chars, $groups, $delimiter, $lowcase);
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if an unique attribute value is unique.
   * Returns TRUE if the value is unique.
   * @param string $attribute_key
   * @param string $value
   * @param string $data_type
   * @param int $skip_entity_id
   * @return bool
   */
  private function _checkUniqueAttributeValue($attribute_key, $value, $data_type, $skip_entity_id = 0)
  {
    $sql = "SELECT t2.`value_id` FROM ";
    $sql.= "`:instance_attribute` as t1,";
    $sql.= "`:instance_attribute_value_:data_type` as t2 WHERE ";
    $sql.= "t1.`attribute_key`=':attribute_key' AND ";
    $sql.= "t2.`attribute_id`=t1.`attribute_id` AND ";
    $sql.= "t2.`value`=':value'";
    $sql.= ((int)$skip_entity_id != 0) ? ' AND t2.entity_id != ":entity_id"' : '';
    if($r = $this->db->query($sql, array(
      ':attribute_key' => $attribute_key,
      ':entity_id' => (int)$skip_entity_id,
      ':value'     => $value,
      ':data_type' => $data_type,
      ':instance'  => $this->eav_instance
    ))->exec() and $r->getNumRows() == 0) {
      return true;
    }
    return false;
  }
}

?>