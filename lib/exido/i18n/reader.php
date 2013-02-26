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
 * Abstract configuration reader.
 * @package    core
 * @subpackage config
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
abstract class I18n_Reader extends ArrayObject
{
  protected $_i18n_line;

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
   * Returns an i18n group.
   * @return string
   */
  public function __toString()
  {
    $string = $this->getArrayCopy();
    if(empty($string)) {
      return '';
    }
    return serialize($string);
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads an i18n group.
   * @param string $group
   * @param array $config
   * @return bool|Config_Reader
   */
  public function load($line, array $i18n = null)
  {
    if($i18n === null) {
      return false;
    }
    // Set group name
    $this->_i18n_line = $line;
    // Clone current object
    $object = clone $this;
    $object->exchangeArray($i18n);
    $this->_i18n_line = null;
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
   * Gets a phrase line. Return a default value if the $key does not found.
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
   * @return I18n_Reader
   */
  public function set($key, $value)
  {
    $this->offsetSet($key, $value);
    return $this;
  }
}

?>