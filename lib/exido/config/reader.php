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
 * Abstract configuration reader.
 * @package    core
 * @subpackage config
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
abstract class Config_Reader extends ArrayObject
{
  protected $_configuration_group;

  // ---------------------------------------------------------------------------

  /**
   * Loads an empty array. Allow using the object properties as array keys.
   */
  public function __construct()
  {
    parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a config group.
   * @return string
   */
  public function __toString()
  {
    return serialize($this->getArrayCopy());
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads a config group.
   * @param string $group
   * @param array $config
   * @return bool|Config_Reader
   */
  public function load($group, array $config = null)
  {
    if($config === null) {
      return false;
    }
    // Set group name
    $this->_configuration_group = $group;
    // Clone current object
    $object = clone $this;
    $object->exchangeArray($config);
    $this->_configuration_group = null;
    return $object;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns an unprocessed array.
   * @return array
   */
  public function asArray()
  {
    return $this->getArrayCopy();
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a config line. Return a default value if the $key doesn't found.
   * @param string $key
   * @param null $default
   * @return mixed|null
   */
  public function get($key, $default = null)
  {
    return $this->offsetExists($key) ? $this->offsetGet($key) : $default;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets a new value.
   * @param string $key
   * @param string $value
   * @return Config_Reader
   */
  public function set($key, $value)
  {
    $this->offsetSet($key, $value);
    return $this;
  }
}

?>