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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade ExidoEngine to newer
 * versions in the future. If you wish to customize ExidoEngine for your
 * needs please refer to http://www.exidoengine.com for more information.
 *
 * @license   http://exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

/**
 * Configuration loader.
 * @package    core
 * @subpackage config
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
class Config_File extends Config_Reader
{
  protected $_directory;
  protected $_configuration_group;
  protected $_configuration_modified = false;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param string $directory
   */
  public function __construct($directory = 'config')
  {
    // Set directory name
    $this->_directory = trim($directory, '/');
    parent::__construct();
  }

  // ---------------------------------------------------------------------------

  /**
   * Merges configurations from all the loaded files.
   * @param string $group
   * @param array $config
   * @return Config_Reader
   */
  public function load($group, array $config = NULL)
  {
    if($files = Exido::findFile($this->_directory, $group)) {
      // Set config array
      $config = array();
      foreach ($files as $file) {
        // Merge each config array to the global array
        $config = arrayMerge($config, require $file);
      }
    }
    return parent::load($group, $config);
  }
}

?>