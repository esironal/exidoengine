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
 * Generates a globally unique identifier.
 * @param int $chars
 * @param int $groups
 * @param string $delimiter
 * @param bool $lowcase
 * @return string
 */
function guidGet($chars = 8, $groups = 1, $delimiter = '', $lowcase = false)
{
  $guid = array();
  if($chars > 64) $chars  = 64;
  if($chars < 1)  $chars  = 4;
  if($groups < 1) $groups = 1;

  for($i = 1; $i <= $groups; $i++) {
    $charid = md5(uniqid(rand(), true)).md5(uniqid(rand(), true));
    if( ! $lowcase) {
      $charid = strtoupper($charid);
    }
    $guid[] = substr($charid, 0, $chars);
  }

  return implode($delimiter, $guid);
}

// -------------------------------------------------------------------------------

/**
 * Generates an unique hash.
 * @param string $word
 * @param bool $lowcase
 * @return string
 */
function guidMD5($word = '', $lowcase = false)
{
  if(empty($word)) {
    $word = md5(uniqid(rand(), true));
  }
  $word = md5($word);
  if( ! $lowcase) {
    $word = strtoupper($word);
  }
  return $word;
}

?>