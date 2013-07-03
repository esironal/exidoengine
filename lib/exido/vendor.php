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
 * Vendor loader.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/10/2012
 * @version    1.0
 */
final class Vendor
{
  /**
   * Loaded vendors
   * @var array
   */
  private static $_vendors = array();

  // ---------------------------------------------------------------------------

  /**
   * Loads a vendor.
   * @param string $vendor
   * @return bool
   */
  public static function load($vendor)
  {
    $_vendors = func_get_args();
    foreach($_vendors as $file) {
      // Does the file exist?  If so, we're done...
      if( ! isset(self::$_vendors[$file])) {
        $path = Exido::findFile('vendors/'.str_replace('_', '/', $file), '_init', true);
        if($path) {
          include_once $path;
          self::$_vendors[$file] = $path;
        }
      }
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a loaded vendors.
   * @return array
   */
  public static function getLoadedVendors()
  {
    return self::$_vendors;
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object
   */
  private function __construct()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object
   */
  final private function __clone()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }
}

?>