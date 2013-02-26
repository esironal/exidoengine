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

include_once 'view/interface/cache/read.php';
include_once 'view/cache/read.php';
include_once 'view/interface/cache/write.php';
include_once 'view/cache/write.php';

/**
 * Abstract view cache class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    05/04/2012
 * @version    1.0
 */
abstract class View_Cache
{
  public $cache_file_pref = 'v-';
  public $cache_file_suff = '.cached_view';
  public $cache_dir_name  = 'e-views';

  protected $_folder;
  protected $_file;
  protected $_lifetime;
  protected $_view_hash;
  protected $_view_data;

  // ---------------------------------------------------------------------------

  /**
   * Sets the cache folder.
   * @param string $folder
   * @return void
   */
  protected function _setFolder($folder)
  {
    $this->_folder = rtrim($folder, '/').'/'.$this->cache_dir_name.'/';
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets the cache life time.
   * @param int $time
   * @return void
   */
  protected function _setLifeTime($time)
  {
    $this->_lifetime = $time;
  }

  // ---------------------------------------------------------------------------

  /**
   * Encodes the query in MD5 hash string.
   * @param string $file
   * @return void
   */
  protected function _encodeViewFile($file)
  {
    $this->_view_hash = md5($file);
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets the cache file name.
   * @return void
   */
  protected function _setCacheFileName()
  {
    $this->_file = $this->cache_file_pref.$this->_view_hash.$this->cache_file_suff;
  }
}

?>