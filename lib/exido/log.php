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

require_once 'log/writer.php';
require_once 'log/file.php';

/**
 * System log class.
 * @package    core
 * @subpackage log
 * @copyright  Sharapov A.
 * @created    15/01/2010
 * @version    1.0
 */
final class Log
{
  /**
   * Default timestamp mask
   * @var string
   */
  public static $ts_format = 'Y-m-d H:i:s';

  /**
   * Singleton instance
   * @var
   */
  private static $_instance;

  /**
   * Log messages
   * @var array
   */
  private $_messages = array();

  /**
   * Log writers
   * @var array
   */
  private $_writers = array();

  // ---------------------------------------------------------------------------

  /**
   * Gets the singleton instance.
   * @return Log
   */
  public static function & instance()
  {
    if(self::$_instance === null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * Attaches a new writer.
   * @param Log_Writer $writer
   * @param array $types
   * @return Log
   */
  public function attach(Log_Writer $writer, array $types = null)
  {
    $this->_writers["{$writer}"] = array
    (
      'object' => $writer,
      'types'  => $types
    );
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Detaches a writer.
   * @param Log_Writer $writer
   * @return Log
   */
  public function detach(Log_Writer $writer)
  {
    unset($this->_writers["{$writer}"]);
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Adds a new message into the log.
   * @param string $type
   * @param string $message
   * @return Log
   */
  public function add($type, $message)
  {
    $this->_messages[] = array
    (
      'time' => gmdate(self::$ts_format),
      'type' => trim($type),
      'body' => trim($message)
    );
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Writes a log messages.
   * @return bool
   */
  public function write()
  {
    if(empty($this->_messages)) {
      // Nothing to do
      return false;
    }
    $messages = $this->_messages;
    $this->_messages = array();

    foreach($this->_writers as $writer) {
      if(empty($writer['types'])) {
        // Save message
        $writer['object']->write($messages);
      } else {
        $filtered = array();
        foreach($messages as $message) {
          if(in_array($message['type'], $writer['types'])) {
            $filtered[] = $message;
          }
        }
        $writer['object']->write($filtered);
      }
    }
    return true;
  }
}

?>