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
 * View cache reader.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    05/04/2012
 * @version    1.0
 */
class View_Cache_Read extends View_Cache implements View_Interface_Cache_Read
{
  /**
   * Constructor. Sets the cache folder, lifetime and file name.
   * @param Config_File $config
   */
  public function __construct(Config_File $config)
  {
    $this->_setFolder($config->cache_folder);
    $this->_setLifeTime($config->cache_lifetime);
    $this->_encodeViewFile($config->file);
    $this->_setCacheFileName();
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a cached view.
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
      @fclose($fp);

      // Get cache time
      $cache_data = explode(':|', $cache, 2);
      if( ! is_numeric($cache_data[0]) or time() > $cache_data[0]) {
        @unlink($this->_folder.$this->_file);
        return false;
      }
      if( ! isset($cache_data[1])) {
        return '';
      }
      return '<!--Cached at:'.$cache_data[0].')-->'.$cache_data[1].'<!--/Cached at:'.$cache_data[0].')-->';
    }
    return false;
  }
}

?>