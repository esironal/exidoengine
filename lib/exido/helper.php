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
 * Helper loader.
 * @package    core
 * @copyright  Sharapov A.
 * @created    06/07/2010
 * @version    1.0
 */
final class Helper
{
  /**
   * Loaded helper list
   * @var array
   */
  private static $_helpers = array();

  // ---------------------------------------------------------------------------

  /**
   * Loads a helper.
   * @param string $helper
   * @return bool
   */
  public static function load($helper)
  {
    $_helpers = func_get_args();
    foreach($_helpers as $file) {
      // Does the class exist?  If so, we're done...
      if( ! isset(self::$_helpers[$file])) {
        $path = Exido::findFile('helper/', $file, true);
        if($path) {
          include_once $path;
          self::$_helpers[$file] = $path;
        }
      }
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a loaded helpers.
   * @return array
   */
  public static function getLoadedHelpers()
  {
    return self::$_helpers;
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