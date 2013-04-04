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
 * Strip string for specified length.
 * @param string $str
 * @param int $length
 * @param string $delimiter
 * @param string $attach
 * @return string
 */
function stringStrip($str, $length, $delimiter = '', $attach = '...')
{
  if( ! is_int($length))
    return $str;
  $str = substr($str, 0, $length);
  if(empty($delimiter))
    return $string;
  $str = substr($str, 0, strrpos($str, $delimiter));
  return (empty($str)) ? $str : $str.$attach;
}

// ---------------------------------------------------------------------------

/**
 * Removes single and double quotes from a string.
 * @param string $str
 * @return mixed
 */
function stringQuotes($str)
{
  return str_replace(array('"', "'"), '', $str);
}

// ---------------------------------------------------------------------------

/**
 * Converts single and double quotes to entities.
 * @param string $str
 * @return mixed
 */
function stringConvertQuotes($str)
{
  return str_replace(array("\'","\"","'",'"'), array("&#39;","&quot;","&#39;","&quot;"), $str);
}

// ---------------------------------------------------------------------------

/**
 * Converts double slashes in a string to a single slash,
 * except those found in http://
 *
 * http://www.some-site.com//index.php
 *
 * becomes:
 *
 * http://www.some-site.com/index.php
 * @param string $str
 * @return mixed
 */
function stringReduceDoubleSlashes($str)
{
  return preg_replace("#([^:])//+#", "\\1/", $str);
}

// -------------------------------------------------------------------------------

/**
 * Generates a random string.
 * @param string $template
 * @param int $chars
 * @return string
 */
function stringRandom($template = '', $chars = 32)
{
  if($chars > 32) $chars = 64;
  if($chars < 1)  $chars = 8;
  if( ! empty($template)) {
    return substr(md5($template), 0, $chars);
  }
  return substr(md5(uniqid(rand(), true)), 0, $chars);
}

// ---------------------------------------------------------------------------

/**
 * Reduces multiple instances of a particular character. Example:
 *
 * Fred, Bill,, Joe, Jimmy
 *
 * becomes:
 *
 * Fred, Bill, Joe, Jimmy
 * @param string $str
 * @param string $character
 * @param bool $trim
 * @return mixed|string
 */
function stringReduceMultiples($str, $character = ',', $trim = false)
{
  $str = preg_replace('#'.preg_quote($character, '#').'{2,}#', $character, $str);
  if($trim === true)
    $str = trim($str, $character);
  return $str;
}

// ---------------------------------------------------------------------------

/**
 * String repeater.
 * @param string $str
 * @param int $num
 * @return string
 */
function stringRepeat($str, $num = 1)
{
  return (($num > 0) ? str_repeat($str, $num) : '');
}

// ---------------------------------------------------------------------------

/**
 * Make entity from the string. Removes all dangerous characters, replace spaces.
 * @param string $str
 * @param bool $lowercase
 * @return string
 */
function stringMakeEntity($str, $lowercase = true)
{
  $str = str_replace(array(' ', '_'), '-', $str);
  $str = preg_replace("#[^\w\-]#", '', $str);
  if($lowercase)
    $str = stringToLower($str);
  return $str;
}

// ---------------------------------------------------------------------------

/**
 * Prevents sandwiching null characters between ascii characters, like Java\0script.
 * @param string $str
 * @param bool $url_encoded
 * @return string
 */
function stringRemoveInvisibleChars($str, $url_encoded = true)
{
  $non_display = array();
  // every control character except newline
  // carriage return, and horizontal tab
  if($url_encoded) {
    $non_display[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
    $non_display[] = '/%1[0-9a-f]/'; // url encoded 16-31
  }
  $non_display[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127
  do $str = preg_replace($non_display, '', $str, -1, $count);
  while ($count);
  return $str;
}

// ---------------------------------------------------------------------------

/**
 * Replace the string symbols with another symbols.
 * Remove all symbols except alphanumeric.
 * Usefull for transliteration cyrillic strings.
 * @param string $haystack
 * @param array $keypairs
 * @param bool $lowercase
 * @return string
 */
function stringChange($haystack, array $keypairs, $lowercase = true)
{
  if(empty($keypairs))
    return '';
  $haystack = str_replace(" ","", $haystack);
  // Remove all characters except alphanumeric
  $haystack = preg_replace("/([A-z0-9])+/", '', $haystack);
  $haystack = strtr($haystack, $keypairs);
  if($lowercase)
    $haystack = stringToLower($haystack);
  return empty($haystack) ? '' : $haystack;
}

// ---------------------------------------------------------------------------

/**
 * Make a string lowercase. Use mb_strtolower if possible.
 * @param string $str
 * @return string
 */
function stringToLower($str)
{
  return function_exists('mb_strtolower') ? mb_strtolower($str) : strtolower($str);
}

// ---------------------------------------------------------------------------

/**
 * Make a string lowercase. Use mb_strtolower if possible.
 * @param string $str
 * @return string
 */
function stringNull($str)
{
  return empty($str) ? null : $str;
}

?>