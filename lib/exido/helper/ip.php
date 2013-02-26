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
 * Check if the target IP is in selected interval.
 * Return TRUE if IP matches the interval, otherwise FALSE
 * @param array $ip checked ip
 * @param array $ip_start
 * @param array $id_end
 * @return bool
 */
function ipCheckRange(array $ip, array $ip_start, array $ip_end)
{
  for($i=0; $i<4; $i++) {
    // If we're using mask
    if($ip_start[$i] == '*' || $ip_end[$i] == '*') {
      // Go next
      continue;
    }
    // Check the target block between start and end blocks,
    // If TRUE so the whole target IP is in range
    elseif ($ip[$i] >= $ip_start[$i] && $ip[$i] < $ip_end[$i]) {
      break;
    }
    // The target block matches start or end block
    elseif ($ip[$i] == $ip_start[$i] || $ip[$i] == $ip_end[$i]) {
      // Go next
      continue;
    }
    else {
      // IP is not in interval
      return false;
    }
  }
  return true;
}

// ---------------------------------------------------------------------------

/**
 * Deploy the subnet mask.
 * @param array $ip
 * @param array $mask
 * @return array
 */
function ipSubnet(array $ip, $mask)
{
  // Count IP in mask
  $ip_count=Array(32=>0, 31=>1, 30=>3, 29=>7, 28=>15, 27=>31, 26=>63,
                  25=>127, 24=>255, 23=>511, 22=>1023, 21=>2047, 20=>4095,
                  19=>8191, 18=>16383, 17=>32767, 16=>65535, 15=>131071,
                  14=>262143, 13=>524287, 12=>1048575, 11=>2097151,
                  10=>4194303, 9=>8388607, 8=>16777215, 7=>33554431,
                  6=>67108863, 5=>134217727, 4=>268435455, 3=>536870911,
                  2=>1073741823);
  $x    = Array();
  $ips  = $ip_count[$mask];
  $x[0] = $ip[0] + intval($ips/(256 * 256 * 256));
  $ips  = ($ips % (256 * 256 * 256));
  $x[1] = $ip[1] + intval($ips/(256 * 256));
  $ips  = ($ips % (256 * 256));
  $x[2] = $ip[2] + intval($ips/(256));
  $ips  = ($ips % 256);
  $x[3] = $ip[3] + $ips;
  return ($x);
}

// ---------------------------------------------------------------------------

/**
 * Parse an IP range. Return FALSE if an input string is incorrect.
 * @param string $range
 * @return array
 */
function ipRangeParser($range)
{
  $range = trim($range);
  // Check range x.x.x.x-y.y.y.y
  if(strpos($range,"-")) {
    $tmp      = explode("-", $range);
    $ip_start = explode(".", $tmp[0]);
    $ip_end   = explode(".", $tmp[1]);
  }
  // Check range x.x.x.x/y
  elseif (strpos($range,"/")) {
    $tmp      = explode("/", $range);
    $ip_start = explode(".", $tmp[0]);
    // Deploy the subnet mask
    $ip_end   = ipSubnet($ip_start, $tmp[1]);
  }
  // Check range x.x.*.* or a single IP
  else
    $ip_start = $ip_end = explode(".", $range);
  // Check if the ranges are correct
  if (count($ip_start) == 4 && count($ip_end) == 4)
    return array($ip_start, $ip_end);
  else
    return false;
}

?>