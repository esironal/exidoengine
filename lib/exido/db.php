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

include_once 'database/init.php';

/**
 * Main database class.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    06/02/2010
 * @version    1.0
 */
class Exido_Database extends Exido_Database_Abstract
{
  /**
   * Singleton instance
   * @var
   */
  protected $db;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param null $db
   * @param null $config
   */
  public function __construct($db = null, $config = null)
  {
    // Set a name
    if($db !== null) {
      $this->db = $db;
    } else {
      $this->db = 'default';
    }
    // Initialize a new DB object
    $this->db = Database_Init::instance((string)$this->db, $config);
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles methods that do not exist.
   * @param string $method
   * @param array $args
   * @return void
   * @throws Exception_Exido
   */
  public function __call($method, array $args)
  {
    throw new Exception_Exido('You suppose to use an undefined method %s::%s(%s)', array(get_called_class(), $method, implode(', ', $args)));
  }
}

?>