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
 * Convert to associative.
 * @param array $array
 * @return array
 */
function arrayToAssoc(array $array)
{
  $assoc = array();
  foreach($array as $data) {
    $assoc[reset($data)] = end($data);
  }
  return $assoc;
}

// -----------------------------------------------------------------------------

/**
 * Checks if the input array is associative.
 * @param array $array
 * @return bool
 */
function arrayAssoc(array $array)
{
  $keys = array_keys($array);
  return array_keys($keys) !== $keys;
}

// -----------------------------------------------------------------------------

/**
 * Gets the value using the "dot-noted" string.
 * @param array $array
 * @param string $path
 * @param null $default
 * @return null
 */
function arrayPath($array, $path, $default = null)
{
  // Split the string
  $keys = explode('.', $path);
  while($keys) {
    $key = array_shift($keys);
    if(ctype_digit($key)) {
      $key = (int)$key;
    }
    if(isset($array[$key])) {
      if($keys) {
        if(is_array($array[$key])) {
          // Got to next key
          $array = $array[$key];
        } else {
          // Key isn't found
          break;
        }
      } else {
        // Return founded value
        return $array[$key];
      }
    } else {
      // Key isn't found
      break;
    }
  }
  // Return default value
  return $default;
}

// -----------------------------------------------------------------------------

/**
 * Returns an array with numbers.
 * @param int $step
 * @param int $max
 * @return array
 */
function arrayRange($step = 10, $max = 100)
{
  if($step < 1)
    return array();
  $array = array();
  for($i = $step; $i <= $max; $i += $step)
    $array[$i] = $i;
  return $array;
}

// -----------------------------------------------------------------------------

/**
 * Returns an array with random numbers in specified range.
 * @param int $count
 * @param int $start
 * @param int $end
 * @return array
 */
function arrayRandom($count = 100, $start = 0, $end = 10000)
{
  $array = array();
  for($i = 0; $i < 200; $i++)
    $array[] = mt_rand(1, 1000);
  return $array;
}

// -----------------------------------------------------------------------------

/**
 * Gets the key value. Return $default value if the key doesn't exists.
 * @param array $array
 * @param string $key
 * @param null $default
 * @return null
 */
function arrayGet(array $array, $key, $default = null)
{
  return isset($array[$key]) ? $array[$key] : $default;
}

// -----------------------------------------------------------------------------

/**
 * Gets the array of key values. If one of the key does not found, returns $default
 * value for it.
 * @param array $array
 * @param array $keys
 * @param null $default
 * @return array
 */
function arrayExtract(array $array, array $keys, $default = null)
{
  $found = array();
  foreach($keys as $key)
    $found[$key] = isset($array[$key]) ? $array[$key] : $default;
  return $found;
}

// -----------------------------------------------------------------------------

/**
 * Inserts value on the top of an array.
 * @param array $array
 * @param string $key
 * @param mixed $val
 * @return array
 */
function arrayUnshift(array $array, $key, $val)
{
  $array = array_reverse($array, true);
  $array[$key] = $val;
  $array = array_reverse($array, true);
  return $array;
}

// -----------------------------------------------------------------------------

/**
 * Merges two or more arrays with recursive. This function isn't
 * a duplicate of array_merge_recursive()
 * @param array $a1
 * @param array ...
 * @return array
 */
function arrayMerge(array $a1)
{
  $result = array();
  for($i = 0, $total = func_num_args(); $i < $total; $i++) {
    foreach(func_get_arg($i) as $key => $val) {
      if(isset($result[$key])) {
        if(is_array($val)) {
          // Recursive merging
          $result[$key] = arrayMerge($result[$key], $val);
        } elseif(is_int($key)) {
          // Add value for indexed array
          array_push($result, $val);
        } else {
          // Replace value for associated array
          $result[$key] = $val;
        }
      } else {
        // Add a new values
        $result[$key] = $val;
      }
    }
  }
  return $result;
}

// -----------------------------------------------------------------------------

/**
 * Strip slashes from array variables.
 * @param mixed $value
 * @return array|string
 */
function arrayStripSlashes($value)
{
  return is_array($value) ? array_map('arrayStripSlashes', $value) : stripslashes($value);
}

// -----------------------------------------------------------------------------

/**
 * Replaces an array values with the values of another array.
 * @param array $array1
 * @param array $array2
 * @return array
 */
function arrayOverwrite(array $array1, array $array2)
{
  foreach(array_intersect_key($array2, $array1) as $key => $value) {
    $array1[$key] = $value;
  }
  if(func_num_args() > 2) {
    foreach(array_slice(func_get_args(), 2) as $array2) {
      foreach(array_intersect_key($array2, $array1) as $key => $value) {
        $array1[$key] = $value;
      }
    }
  }
  return $array1;
}

// -----------------------------------------------------------------------------

/**
 * Remove array keys that have an empty values.
 * @param array $array
 * @return array
 */
function arrayClean(array $array)
{
  foreach($array as $key => $value) {
    if(empty($value))
      unset($array[$key]);
  }
  return $array;
}

?>