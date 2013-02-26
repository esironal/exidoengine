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
 * Log file loader.
 * @package    core
 * @subpackage log
 * @copyright  Sharapov A.
 * @created    10/02/2010
 * @version    1.0
 */
class Log_File extends Log_Writer
{
  // Log directory
  protected $_directory;

  // ---------------------------------------------------------------------------

  /**
   * Creates a new log file.
   * @param string $directory
   * @throws Exception_Exido
   */
  public function __construct($directory)
  {
    // Check if the specified directory exists and available for writing.
    if( ! is_dir($directory)) {
      if( ! @mkdir($directory, DIR_WRITE_MODE, true)) {
        throw new Exception_Exido("Couldn't create a log directory");
      }
    }
    // Set log path
    $this->_directory = rtrim(str_replace('\\', '/', realpath($directory)), '/').'/';
  }

  // ---------------------------------------------------------------------------

  /**
   * Saves a messages to the log file.
   * @param array $messages
   * @return bool|void
   */
  public function write(array $messages)
  {
    // Set directory name with current date.
    $directory = $this->_directory.gmdate('Y-m').'/';

    // If the directory doesn't exist, so we try to create it.
    if( ! is_dir($directory)) {
      @mkdir($directory, DIR_WRITE_MODE, true);
    }

    // Set file name
    $filename = $directory.gmdate('d').'.log';

    // Set row format
    $format = '[time] [type] body';

    if( ! $fp = @fopen($filename, FOPEN_WRITE_CREATE)) {
      return false;
    }
    flock($fp, LOCK_EX);
    // Save each message to the log file.
    foreach($messages as $message) {
      fwrite($fp, strtr($format, $message).EXIDO_EOL);
    }
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($filename, FILE_WRITE_MODE);
    return true;
  }
}

?>