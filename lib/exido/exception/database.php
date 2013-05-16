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
 * Main exido exception class.
 * @package    core
 * @subpackage exception
 * @copyright  Sharapov A.
 * @created    26/10/2011
 * @version    1.0
 */
class Exception_Database extends Exception_Exido
{
  public $errstr;
  public $errquery;

  // ---------------------------------------------------------------------------

  /**
   * Constructor. Throws an error returned by SQL server.
   * @param Database_Adapter $adapter
   */
  public function __construct(Database_Adapter $adapter)
  {
    $this->errquery = $adapter->getLastQuery();
    $this->errstr   = $adapter->getErrorText();
    parent::__construct($this->errstr, array(), $adapter->getErrorNo());
  }
}

?>