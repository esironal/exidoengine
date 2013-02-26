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
 * Sets cookie. Accepts six parameter, or you can submit an associative
 * array in the first parameter containing all the values.
 * @param string $name
 * @param string $value
 * @param string $expire
 * @param string $domain
 * @param string $path
 */
function cookieSet($name = '', $value = '', $expire = '', $domain = '', $path = '/')
{
  if(is_array($name)) {
    foreach(array('value', 'expire', 'domain', 'path', 'prefix', 'name') as $item) {
      if(isset($name[$item])) {
        $$item = $name[$item];
      }
    }
  }

  if( ! is_numeric($expire)) {
    $expire = time() - 86500;
  } else {
    if($expire > 0) {
      $expire = time() + $expire;
    } else {
      $expire = 0;
    }
  }
  setcookie($name, $value, $expire, $path, $domain, 0);
}

// ---------------------------------------------------------------------------

/**
 * Fetches an item from the COOKIE array
 * @param string $key
 * @return array|string
 */
function cookieGet($key)
{
  $input = Input::instance();
  return $input->cookie($key);
}

// ---------------------------------------------------------------------------

/**
 * Drops a cookie
 * @param string $name
 * @param string $domain
 * @param string $path
 */
function cookieDelete($name = '', $domain = '', $path = '/')
{
  setcookie($name, '', (time() - 360000), $path, $domain, 0);
}

?>