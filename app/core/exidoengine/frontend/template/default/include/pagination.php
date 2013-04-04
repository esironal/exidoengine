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

print '<ul class="-i-pgn-ul">'.EXIDO_EOL;
if( ! empty($view->first_page)) {
  print '<li class="-i-pgn-li -i-pgn-li_first_page">'
    .'<a href="'.$view->first_page.'" title="'.__('Go to first page').'">&laquo;</a>'
    .'</li>'.EXIDO_EOL;
}
if( ! empty($view->prev_page)) {
  print '<li class="-i-pgn-li -i-pgn-li_prev_page">'
    .'<a href="'.$view->prev_page.'" title="'.__('Go to previous page').'">&lt;</a>'
    .'</li>'.EXIDO_EOL;
}
for($loop = $view->start; $loop <= $view->end; $loop++) {
  if($loop > 0) {
    if($view->cur_page == $loop)
      print '<li class="-i-pgn-li -i-pgn-li_loop -i-pgn-li_current">'.$view->cur_page.'</li>'.EXIDO_EOL;
    else {
      print '<li class="-i-pgn-li -i-pgn-li_loop">';
      print '<a href="'.$view->base_url.$loop.$view->url_attach.'">'.$loop.'</a>';
      print '</li>'.EXIDO_EOL;
    }
  }
}
if( ! empty($view->next_page)) {
  print '<li class="-i-pgn-li -i-pgn-li_next_page">'
    .'<a href="'.$view->next_page.'" title="'.__('Go to next page').'">&gt;</a>'
    .'</li>'.EXIDO_EOL;
}
if( ! empty($view->last_page)) {
  print '<li class="-i-pgn-li -i-pgn-li_last_page">'
    .'<a href="'.$view->last_page.'" title="'.__('Go to last page').'">&raquo;</a>'
    .'</li>'.EXIDO_EOL;
}
print '</ul>'.EXIDO_EOL;

?>