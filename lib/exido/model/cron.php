<?php defined('SYSPATH') or die('No direct script access allowed.');

/*******************************************************************************
 * ExidoEngine Web-sites manager
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (3.0)
 * that is bundled with this package in the file license_gpl.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
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
 * @license   http://www.gnu.org/copyleft/gpl.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2013, ExidoEngine Solutions
 * @link      http://www.exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

/**
 * Model cron
 * @package    core
 * @subpackage model
 * @copyright  Sharapov A.
 * @created    01/09/2012
 * @version    1.0
 */
final class Model_Cron
{
  /**
   * Constructor. Load helpers
   */
  public function __construct()
  {
    // Load helpers
    Helper::load('file', 'date');
  }

  // ---------------------------------------------------------------------------

  /**
   * Clear expired cache files
   * @return array
   */
  public function clearCache()
  {
    $removed = array();
    // Get cache files of the VIEW object
    $view_cache_dir      = Exido::config('view.cache_folder');
    // Get the cache life time
    $view_cache_lifetime = Exido::config('view.cache_lifetime');
    // Get files list
    $files = fileList(rtrim($view_cache_dir, '/').'/e-view', false);
    // Check each file
    foreach($files as $file) {
      // Get information of the file
      if($stat = stat($file)) {
        // Check if the difference between the current server time and the cache life time
        // is less then time of last access
        if((time() - $view_cache_lifetime) > $stat['atime']) {
          @unlink($file);
          $removed[] = $file;
        }
      }
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles methods that do not exist.
   * @param string $method
   * @param array $args
   * @return string
   */
  public function __call($method, array $args)
  {
    return sprintf(__('Undefined cron method %s:%s(%s)'), __CLASS__, $method, implode(', ', $args));
  }
}

?>