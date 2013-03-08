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
 * Administrator page controller class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    10/11/2012
 * @version    1.0
 */
class Administrator_Controller_Page_Action extends Controller_Administrator_Abstract
{
  public $db_page;
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    Helper::load('table', 'date', 'eav', 'form');
    // Load EAV model
    $this->db_page = $this->model('Model_Eav', 'page');
  }

  // ---------------------------------------------------------------------------

  /**
   * Pages index page
   * @return void
   */
  public function index()
  {
    $this->view->item_list = $this->db_page->getEntities();
  }

  // ---------------------------------------------------------------------------

  /**
   * Pages create page
   * @return void
   */
  public function create()
  {
    // Save when posting
    if($this->input->checkPost()) {
      $post = $this->input->post();
      if($this->db_page->addEntity($post)) {
        $this->session->set('action_success', __('Page has been successfully created.'));
      } else {
        // Error when saving
        $this->session->set('action_error',
                              sprintf(__('There is an error while creating a page. Details: %s'), implode(', ', $this->db_page->getErrors()))
        );
      }
      uriSiteRedirect('page/list');
    }
    $this->view->attribute_form = $this->db_page->getAttributeSet('default');
  }

  // ---------------------------------------------------------------------------

  /**
   * Pages edit page
   * @param int $entity_id
   * @return void
   * @throws Exception_Exido
   */
  public function edit($entity_id = null)
  {
    // Get page data
    if( ! $this->view->attribute_form = $this->db_page->getEntityById($entity_id)) {
      throw new Exception_Exido(__('Page not found'), array(), 404);
    };
    // Save when posting
    if($this->input->checkPost()) {
      $post = $this->input->post();
      if($this->db_page->editAttributeValues($entity_id, $post)) {
        // Errors
        $this->session->set('action_success', __('Page has been successfully saved.'));
      } else {
        // Error when saving
        $this->session->set('action_error',
                              sprintf(__('There is an error while saving a page. Details: %s'), implode(', ', $this->db_page->getErrors()))
        );
      }
      uriSiteRedirect('page/list');
    }
  }
}

?>