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

/**
 * Database cache reader.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/04/2012
 * @version    1.0
 */
class Database_Cache_Read extends Database_Cache implements Database_Interface_Cache_Read
{
  /**
   * Constructor.  Sets the cache folder, life time and encodes the query hash.
   * @param Database_Adapter $adapter
   */
  public function __construct(Database_Adapter $adapter)
  {
    $this->_setFolder($adapter->cache_folder);
    $this->_setLifeTime($adapter->cache_lifetime);
    $this->_encodeQuery($adapter->last_sql);
    $this->_setCacheFileName();
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a result from cache.
   * @return bool|mixed
   */
  public function getCache()
  {
    if(is_file($this->_folder.$this->_file)) {
      if(($fp = @fopen($this->_folder.$this->_file, FOPEN_READ)) === false) {
        @unlink($this->_folder.$this->_file);
        return false;
      }

      // Read cache file
      if(($cache = @fread($fp, filesize($this->_folder.$this->_file))) === false) {
        @unlink($this->_folder.$this->_file);
        return false;
      }
      fclose($fp);

      // Get cache time
      $cache_data = explode(':|', $cache, 2);
      if(time() > $cache_data[0]) {
        @unlink($this->_folder.$this->_file);
        return false;
      }
      return @unserialize($cache_data[1]);
    }
    return false;
  }
}

?>