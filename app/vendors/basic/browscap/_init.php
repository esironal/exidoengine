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
 * @license   http://exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

include_once 'Browscap.php';

/*******************************************************************************
 * FUNCTIONS USED IN EXIDOENGINE
 * The name of function should start with prefix "vendor"
 * and the name of a folder where the vendor is placed.
 *
 * For example: vendorMaxmindGeoipFunctionName()
 *******************************************************************************/

/**
 * Get information about the user browser.
 * @return array
 */
function vendorBasicBrowscapGetBrowser()
{
  if(ini_get('browscap')) {
    return get_browser();
  }
  $bc = new Browscap(APPPATH.'data/cache');
  return $bc->getBrowser();
}

?>