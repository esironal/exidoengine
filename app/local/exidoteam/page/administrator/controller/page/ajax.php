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
 * Administrator page controller class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/11/2012
 * @version    1.0
 */
class Administrator_Controller_Page_Ajax extends Controller_Ajax_Abstract
{
  public $db_page;

  // ---------------------------------------------------------------------------

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    // Load EAV model
    $this->db_page = $this->model('Model_Eav', 'page');
  }

  // ---------------------------------------------------------------------------

  /**
   * Pages index page
   * @return void
   */
  public function unique()
  {
    $this->disableViews();
    // Get parameters
    $params = $this->input->get();
    // Check if we have entity_id in $_GET
    if(isset($params['entity_id'])) {
      $entity_id = (int)$params['entity_id'];
      unset($params['entity_id']);
    } else
      $entity_id = 0;
    // Check if all the values are unique
    if($this->db_page->checkIfValueIsUnique($params, $entity_id)) {
      print 'true';
    } else {
      print 'false';
    }
  }
}

?>