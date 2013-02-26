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
 * Converts an SQL timestamp into the specified format.
 * @param string $time
 * @param string $format
 * @return string
 */
function dateConvertSQL2Human($time, $format)
{
  if($time == '0000-00-00 00:00:00' or empty($time)) {
    return '';
  }
  return strftime($format, dateConvert2Unix($time));
}

// ---------------------------------------------------------------------------

/**
 * Converts a UNIX timestamp into the specified format.
 * @param int $time
 * @param string $format
 * @return string
 */
function dateConvert2Human($time, $format)
{
  return strftime($format, $time);
}

// ---------------------------------------------------------------------------

/**
 * Converts a UNIX timestamp (server local timestamp) into the specified format.
 * @param string $format
 * @return string
 */
function dateGetLocal($format)
{
  return strftime($format, time());
}

// ---------------------------------------------------------------------------

/**
 * Converts a UNIX timestamp (GMT timestamp) into the specified format.
 * @param string $format
 * @return string
 */
function dateGetGMT($format)
{
  return gmstrftime($format, time());
}

// ---------------------------------------------------------------------------

/**
 * Converts a UNIX timestamp into SQL timestamp.
 * @param null $time
 * @return string
 */
function dateConvert2SQL($time = null)
{
  if(empty($time)) {
    $time = time();
  }
  return strftime('%Y-%m-%d-%H-%M-%S', $time);
}

// ---------------------------------------------------------------------------

/**
 * Converts a UNIX timestamp to GMT timestamp.
 * @param null $time
 * @return int
 */
function dateConvert2Gmt($time = null)
{
  if(empty($time)) {
    $time = time();
  }
  return mktime(
         gmdate("H", $time),
         gmdate("i", $time),
         gmdate("s", $time),
         gmdate("m", $time),
         gmdate("d", $time),
         gmdate("Y", $time)
         );
}

// ---------------------------------------------------------------------------

/**
 * Converts a MySQL timestamp into the UNIX timestamp.
 * @param null $time
 * @return int
 */
function dateConvert2Unix($time = null)
{
  if(empty($time)) {
    $time = time();
  }
  $time = str_replace(array('-', ':', ' '), '', $time);
  // YYYYMMDDHHMMSS
  return  mktime(
          substr($time, 8, 2),
          substr($time, 10, 2),
          substr($time, 12, 2),
          substr($time, 4, 2),
          substr($time, 6, 2),
          substr($time, 0, 4)
          );
}

// ---------------------------------------------------------------------------

/**
 * Calculates a user's age. Accepts date in format YYYY-MM-DD
 * @param string $dob
 * @return bool|int
 */
function dateCalculateAge($dob)
{
  if( ! preg_match('/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/', $dob)) {
    return false;
  }
  list($year, $month, $day) = explode("-", $dob);
  $year_diff  = date("Y") - $year;
  $month_diff = date("m") - $month;
  $day_diff   = date("d") - $day;
  if($month_diff < 0) {
    $year_diff--;
  } elseif (($month_diff == 0) && ($day_diff < 0)) {
    $year_diff--;
  }
  return $year_diff;
}

?>