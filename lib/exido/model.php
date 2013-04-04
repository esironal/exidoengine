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
 * Model registry class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    14/06/2010
 * @version    1.0
 */
final class Model
{
  /**
   * Loaded model list
   * @var array
   */
  private $_models = array();

  // ---------------------------------------------------------------------------

  /**
   * Loader.
   * @param string $model
   * @param null $params
   * @return bool|object
   */
  public function load($model, $params = null)
  {
    // Does the model exist? If so, we're done...
    if(isset($this->_models[$model])) {
      return $this->_models[$model];
    }
    // Load model class
    $this->_models[$model] = Registry::factory($model, $params);

    // Get object
    return $this->get($model);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a model object by its class name.
   * @param string $class
   * @return object|bool
   */
  public function get($class)
  {
    // Does the class exist?  If so, we're done...
    if(isset($this->_models[$class])) {
      return $this->_models[$class];
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a loaded models.
   * @return array
   */
  public function getLoaded()
  {
    return $this->_models;
  }

  // ---------------------------------------------------------------------------

  /**
   * Removes a model object by its class name.
   * @param $class
   * @return void
   */
  public function remove($class)
  {
    unset($this->_models[strtolower($class)]);
  }
}

?>