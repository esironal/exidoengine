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

require_once 'i18n/file.php';

/**
 * Wrapper for language arrays.
 * @package    core
 * @subpackage i18n
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
final class I18n
{
  /**
   * Loaded lines list
   * @var array
   */
  protected $_lines = array();

  /**
   * Singleton instance
   * @var
   */
  private static $_instance;

  // ---------------------------------------------------------------------------

  /**
   * Gets the singleton instance.
   * @return I18n
   */
  public static function & instance()
  {
    if(self::$_instance === null) {
      // Create a new instance
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * Attaches a new language reader.
   * @param I18n_File $reader
   * @return I18n
   */
  public function attach(I18n_File $reader)
  {
    $this->_lines = $reader->load();
    return $this;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the translated line.
   * @param string $line
   * @return string
   */
  public function get($line)
  {
    if(isset($this->_lines[$line])) {
      return $this->_lines[$line];
    }
    return $line;
  }
}

?>