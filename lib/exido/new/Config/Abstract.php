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
 * Wrapper for configuration arrays.
 * @package    core
 * @subpackage config
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
abstract class Exido_Config_Abstract
{
  /**
   * Configuration readers
   * @var array
   */
  protected $_readers = array();

  // Singleton static instance
  private static $_instance;

  // ---------------------------------------------------------------------------

  /**
   * Gets the singleton instance.
   * @return Exido_Config_Abstract
   */
  public static function & instance()
  {
    if(self::$_instance === null) {
      // Create a new instance
      self::$_instance = new Exido_Config();
    }
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * Attaches a new configuration reader.
   * @param Exido_Config_Reader_Abstract $reader
   * @param bool $first
   * @return Exido_Config_Abstract
   */
  public function attach(Exido_Config_Reader_Abstract $reader, $first = true)
  {
    if($first === true) {
      // Place the reader at the top of the stack
      array_unshift($this->_readers, $reader);
    } else {
      // Place the reader at the bottom of the stack
      $this->_readers[] = $reader;
    }
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Detaches a configuration reader.
   * @param Exido_Config_Reader_Abstract $reader
   * @return Exido_Config_Abstract
   */
  public function detach(Exido_Config_Reader_Abstract $reader)
  {
    if(($key = array_search($reader, $this->_readers))) {
      // Remove the writer
      unset($this->_readers[$key]);
    }
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads a configuration group. Searches the readers in order until the
   * group is found. If the group does not exist, an empty configuration
   * array will be loaded using the first reader.
   * @param string $group
   * @return mixed
   * @throws Exception_Exido
   */
  public function load($group)
  {
    foreach($this->_readers as $reader) {
      if($config = $reader->load($group)) {
        // Found a reader for this configuration group
        return $config;
      }
    }
    // Reset the iterator
    reset($this->_readers);
    if( ! is_object($config = current($this->_readers))) {
      throw new Exception_Exido('No configuration readers attached');
    }
    // Load the reader as an empty array
    return $config->load($group, array());
  }

  // ---------------------------------------------------------------------------

  /**
   * Copies one configuration group to all of the other readers.
   * @param string $group
   * @return Exido_Config_Abstract
   */
  public function copy($group)
  {
    // Load the configuration group
    $config = $this->load($group);
    foreach($this->_readers as $reader) {
      if($config instanceof $reader) {
        // Do not copy the config to the same group
        continue;
      }
      // Load the configuration object
      $object = $reader->load($group, array());
      foreach($config as $key => $value) {
        // Copy each value in the config
        $object->offsetSet($key, $value);
      }
    }
    return $this;
  }
}

?>