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
 * Tests for file writability.
 * is_writable() returns TRUE on Windows servers when you really can't write to
 * the file, based on the read-only attribute.  is_writable() is also unreliable
 * on Unix servers if safe_mode is on.
 * @param string $file
 * @return bool
 */
function fileIsReallyWritable($file)
{
  // If we're on a Unix server with safe_mode off we call is_writable
  if(DIRECTORY_SEPARATOR == '/' and @ini_get("safe_mode") == false)
    return is_writable($file);
  // For windows servers and safe_mode "on" installations we'll actually
  // write a file then read it. Bah...
  if(is_dir($file)) {
    $file = rtrim($file, '/').'/'.md5(rand(1,100));
    if(($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false)
      return false;
    fclose($fp);
    @chmod($file, DIR_WRITE_MODE);
    @unlink($file);
    return true;
  } elseif(($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false)
    return false;
  fclose($fp);
  return true;
}

// ---------------------------------------------------------------------------------

/**
 * Returns all the files and directories in a resource path.
 * @param bool $path
 * @param bool $recursive
 * @return array
 */
function fileList($path = false, $recursive = false)
{
  $files = array();
  if($path === false) {
    $paths = array_reverse(Exido::getIncludePaths());
    foreach($paths as $path)
      // Recursively get and merge all files
      $files = array_merge($files, fileList($path, $recursive));
  } else {
    $path = rtrim($path, '/').'/';
    if(is_readable($path)) {
      $items = (array) glob($path.'*');
      if( ! empty($items)) {
        foreach($items as $index => $item) {
          $files[] = $item = str_replace('\\', '/', $item);
          // Handle recursion
          if(is_dir($item) and $recursive == true) {
            // Filename should only be the basename
            $item = pathinfo($item, PATHINFO_BASENAME);
            // Append sub-directory search
            $files = array_merge($files, fileList($path.$item, true));
          }
        }
      }
    }
  }
  return $files;
}

?>