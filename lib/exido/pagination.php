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
 * Pagination class.
 * @package    core
 * @subpackage pagination
 * @copyright  Sharapov A.
 * @created    29/12/2012
 * @version    1.0
 */
final class Pagination
{
  public $base_url        = '';
  public $base_url_attach = '';
  public $total_rows      = 0;
  public $per_page        = 10;
  public $num_links       = 10;
  public $cur_page        = 1;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param null $params
   */
  public function __construct($params = null)
  {
    if($params == null) {
      $params = array();
    }
    $this->setup($params);
  }

  // ---------------------------------------------------------------------------

  /**
   * Setup image preferences.
   * @param array $params
   * @return void
   */
  public function setup(array $params)
  {
    // Convert array elements into class properties
    if(count($params) > 0) {
      foreach($params as $key => $val) {
        $this->$key = $val;
      }
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Resets the values in case this class is used in a loop.
   * @return void
   */
  public function clear()
  {
    $this->base_url        = '';
    $this->base_url_attach = '';
    $this->total_rows      = 0;
    $this->per_page        = 10;
    $this->num_links       = 10;
    $this->cur_page        = 1;
  }

  // ---------------------------------------------------------------------------

  /**
   * Generates a pagination selector.
   * @return string
   */
  public function get()
  {
    if(empty($this->total_rows) or empty($this->per_page))
      return '';
    // Count pages
    $num_pages = $this->_countPages();
    // Return empty if we have one page.
    if($num_pages == 1)
      return '';
    // Get the current page number
    if( ! is_numeric($this->cur_page) or $this->cur_page < 1)
      $this->cur_page  = 1;
    if( ! is_numeric($this->num_links))
      $this->num_links = 2;
    if($this->num_links < 1)
      return '';
    // If the current page number is bigger than total count of a pages
    // so we will need to show the last page
    if($this->cur_page > $num_pages)
      $this->cur_page = $num_pages;

    $view = View::instance();
    // Get start and end page numbers
    $view->start      = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
    $view->end        = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;
    $view->cur_page   = $this->cur_page;
    $view->base_url   = $this->base_url;
    $view->url_attach = $this->base_url_attach;

    // Define variables...
    $view->first_page =
      $view->prev_page =
        $view->next_page =
          $view->last_page = '';

    // Generate link to a first page
    if($this->cur_page > ($this->num_links + 1))
      $view->first_page = $this->base_url.'1'.$this->base_url_attach;
    // Generate link to a previous page
    if($this->cur_page != 1) {
      $i = $this->cur_page - 1;
      if($i <= 0) $i = '1';
      $view->prev_page = $this->base_url.$i.$this->base_url_attach;
    }
    // Generate link to a next page
    if($this->cur_page < $num_pages)
      $view->next_page = $this->base_url.($this->cur_page + 1).$this->base_url_attach;
    // Generate link to a last page
    if(($this->cur_page + $this->num_links) < $num_pages)
      $view->last_page = $this->base_url.$num_pages.$this->base_url_attach;
    // Load a pagination view
    return Registry::factory('View_Custom')
          ->load('pagination')
          ->parse('pagination', $view, new View_Helper);
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns an offset integer.
   * @return int
   */
  public function getOffset()
  {
    // Get the current page
    if( ! is_numeric($this->cur_page) or $this->cur_page < 1)
      $this->cur_page = 1;
    // If the current page number is bigger than total count of a pages
    // so we will need to show the last page
    if($this->cur_page > $this->_countPages())
      $this->cur_page = 1;
    return ($this->cur_page - 1) * $this->per_page;
  }

  // ---------------------------------------------------------------------------

  /**
   * Get number of pages.
   * @return int
   */
  private function _countPages()
  {
    return ceil($this->total_rows / $this->per_page);
  }
}

?>