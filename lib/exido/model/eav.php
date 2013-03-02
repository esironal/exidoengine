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

include_once 'model/db/eav/abstract.php';
include_once 'model/db/abstract.php';

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

    pre($attributes);
    pre($set);
    foreach($set as $key => $f) {
      // Check if the required attribute exists
      if($f->is_required) {
        if( ! isset($attributes[$key]) and empty($attributes[$key]->default_value)) {
          $this->_setError($key, sprintf(__('Attribute %s is required'), $key));
          return false;
        }
      }
    }


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
    $sql.= " ORDER BY `order` DESC";
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
    $sql.= "t2.`entity_id`=':entity_id' AND t2.`attribute_id`=t1.`attribute_id`";
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
}

?>