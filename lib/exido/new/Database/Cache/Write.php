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
 * Database cache writer.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/04/2012
 * @version    1.0
 */
class Exido_Database_Cache_Write extends Exido_Database_Cache //implements Database_Interface_Cache
{
  /**
   * Constructor. Sets the cache folder, life time and encodes the query hash.
   * @param Exido_Database_Result $result
   */
  public function __construct(Exido_Database_Result $result)
  {
    $this->_setFolder($result->cache_folder);
    $this->_setLifeTime($result->cache_lifetime);
    $this->_encodeQuery($result->last_sql);
    $this->_setCacheFileName();
    $this->_query_data = $result->result_object;
  }

  // ---------------------------------------------------------------------------

  /**
   * Writes a query results.
   * @return bool
   */
  public function setCache()
  {
    // If the cache directory doesn't exist, so we try to create it.
    if( ! is_dir($this->_folder)) {
      if( ! @mkdir($this->_folder, DIR_WRITE_MODE)) {
        return false;
      }
    }

    if(($fp = @fopen($this->_folder.$this->_file, FOPEN_WRITE_CREATE_DESTRUCTIVE)) === false) {
      return false;
    }
    // Set cache life time
    $cache_time = time() + $this->_lifetime;
    if(@fwrite($fp, $cache_time.':|'.serialize($this->_query_data)) === FALSE) {
      @unlink($this->_folder.$this->_file);
      return false;
    }
    @fclose($fp);
    return true;
  }
}

?>