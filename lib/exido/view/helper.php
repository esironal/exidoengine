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

include_once 'view/abstract.php';
include_once 'view/helper/abstract.php';
include_once 'view/helper/library.php';
include_once 'view/helper/meta.php';
include_once 'view/helper/html.php';

/**
 * View helper main class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    14/06/2010
 * @version    1.0
 */
class View_Helper extends View_Helper_Html
{
  /**
   * Singleton instance
   * @var
   */
  private static $_instance;

  // ---------------------------------------------------------------------------

  /**
   * Gets the singleton instance
   * @return View_Helper
   */
  public static function & instance()
  {
    if(self::$_instance === null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }
}
?>