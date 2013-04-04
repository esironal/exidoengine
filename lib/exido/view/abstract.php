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
 * Abstract view class.
 * @package    core
 * @subpackage view
 * @copyright  Sharapov A.
 * @created    17/09/2012
 * @version    1.0
 */
abstract class View_Abstract
{
  /**
   * Cache configuration object
   * @var
   */
  protected $_cache_config;

  // ---------------------------------------------------------------------------

  /**
   * Constructor. Gets the cache configuration
   */
  public function __construct()
  {
    $this->_cache_config = Exido::config('view');
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles view variables that do not exist.
   * @param string $var
   * @return void
   */
  public function __get($var)
  {
    print sprintf(__('[Undefined property %s]'), $var);
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles view methods that do not exist.
   * @param string $method
   * @param array $args
   * @return mixed
   */
  public function __call($method, array $args)
  {
    print sprintf(__('[Undefined method %s(%s)]'), $method, implode(', ', $args));
  }
}
?>