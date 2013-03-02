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
 * Registry of model variables.
 * @package    core
 * @subpackage model
 * @copyright  Sharapov A.
 * @created    01/09/2012
 * @version    1.0
 */
abstract class Model_Mapper
{
  /**
   * Model_Registry object
   * @var
   */
  protected $_aData;

  // ---------------------------------------------------------------------------

  /**
   * Return data array.
   * @return array
   */
  protected function _getData()
  {
    return (array)$this->_aData;
  }

  // ---------------------------------------------------------------------------

  /**
   * Clear data object.
   * @return void
   */
  protected function _clearData()
  {
    $this->_aData = Model_Registry::instance(true);
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles the DB fields and their values.
   * @param string $method
   * @param array $args
   * @return bool|mixed
   * @throws Exception_Exido
   */
  public function __call($method, array $args)
  {
    if(preg_match('/^set(.*)/', $method, $m) and isset($m[1]) and $field = strtolower($m[1])) {
      // Set a new property to the $_aData array
      Helper::load('string');
      $this->_aData->$field = stringNull(reset($args));
      //pre($this->_aData);
      return true;
    }

    if(preg_match('/^get(.*)/', $method, $m) and isset($m[1]) and $field = strtolower($m[1])) {
      // Get a property from the $_aData array
      if( ! isset($this->_aData->$field)) {
        throw new Exception_Exido('You suppose to use an undefined method or property %s::%s', array(get_called_class(), $method));
      }
      return $this->_aData->$field;
    }

    if(preg_match('/^remove(.*)/', $method, $m) and isset($m[1]) and $field = strtolower($m[1])) {
      // Remove a property from the $_aData array
      if( ! isset($this->_aData->$field)) {
        throw new Exception_Exido('You suppose to remove an undefined property %s::%s', array(get_called_class(), $method));
      }
      unset($this->_aData->$field);
      return true;
    }

    throw new Exception_Exido('You suppose to use an undefined method %s::%s(%s)', array(get_called_class(), $method, implode(', ', $args)));
  }
}

?>