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
 * Language loader.
 * @package    core
 * @subpackage i18n
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
class I18n_File
{
  protected $_directory;
  protected $_idiom;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param string $idiom
   * @param string $directory
   */
  public function __construct($idiom, $directory = 'i18n')
  {
    // Set language idiom
    $this->_idiom = $idiom;
    // Set directory name
    $this->_directory = trim($directory, '/');
  }

  // ---------------------------------------------------------------------------

  /**
   * Merges language lines from all the loaded files.
   * @return array
   */
  public function load()
  {
    static $i18n = array();
    if($files = Exido::findFile($this->_directory, $this->_idiom)) {
      // Set config array
      foreach($files as $file) {
        // Merge each config array to the global array
        $i18n = array_merge($i18n, require $file);
      }
    }
    return $i18n;
  }
}

?>