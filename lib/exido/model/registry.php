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
 * Registry of model variables.
 * @package    core
 * @subpackage model
 * @copyright  Sharapov A.
 * @created    01/09/2012
 * @version    1.0
 */
final class Model_Registry extends ArrayObject
{
  /**
   * Singleton instance
   * @var
   */
  private static $_instance;

  // ---------------------------------------------------------------------------

  /**
   * Gets the singleton instance
   * @param bool $re_instantiate
   * @return Registry
   */
  public static function & instance($re_instantiate = false)
  {
    if($re_instantiate) {
      self::$_instance = null;
    }

    if(self::$_instance === null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   */
  final public function __construct()
  {
    parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles the variables that do not exist.
   * @param string $var
   * @return null
   */
  public function __get($var)
  {
    return null;
  }
}

?>