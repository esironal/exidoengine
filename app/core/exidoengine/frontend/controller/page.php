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
 * Frontend page controller class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/11/2012
 * @version    1.0
 */
class Frontend_Controller_Page extends Controller_Abstract
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  // ---------------------------------------------------------------------------

  /**
   * Pages index page
   * @param string $page_key
   * @return void
   */
  public function index($page_key = null)
  {
    $this->view->page = $this->model('Model_Eav', 'page')->getEntityByKey($page_key);
  }
}

?>