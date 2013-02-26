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

/**
 * Validate Email.
 * @param string $email
 * @return bool
 */
function validationEmail($email)
{
  return (bool)preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email);
}

// -----------------------------------------------------------------------------

/**
 * Validate an Alpha string.
 * @param string $str
 * @return bool
 */
function validationAlpha($str)
{
  return (bool)preg_match("/^([A-z])+$/i", $str);
}

// -----------------------------------------------------------------------------

/**
 * Validate an Alpha-numeric string.
 * @param string $str
 * @return bool
 */
function validationAlphaNum($str)
{
  return (bool)preg_match("/^([A-z0-9])+$/i", $str);
}

// -----------------------------------------------------------------------------

/**
 * Validate an Alpha-numeric string with underscores and dashes string.
 * @param string $str
 * @return bool
 */
function validationAlphaDash($str)
{
  return (bool)preg_match("/^([A-z0-9_-])+$/i", $str);
}

// -----------------------------------------------------------------------------

/**
 * Validate an SQL date.
 * @param string $sql_timestamp
 * @return bool
 */
function validationSQLDate($sql_timestamp)
{
  return (bool)preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})-([0-9]{2})-([0-9]{2})-([0-9]{2})$/", $sql_timestamp);
}

?>