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

// List actions menu
$view->action_menu = array(
  '/page/list/create' => __('Create a new page'),
  '/page/list/remove' => __('Remove page')
);
// Include menu code
$view->getView('layout/inc.list-action-menu-panel');

print $helper->heading(__('Static pages'));

if($view->item_list) {
  print tableOpen('-i-table -i-table-striped');
  print tableHead(array(
                    '',
                    __('ID'),
                    __('Page title'),
                    __('Owner'),
                    __('Group'),
                    __('Added at'),
                    __('Status'),
                    __('Actions')
                  ));
  foreach($view->item_list as $item) {
    print '<tr>'
         .'<td>'.formCheckbox('item[]', $item->entity_id, false, 'class="item-list-checkbox"').'</td>'
         .'<td>'.$item->entity_id.'</td>'
         .'<td>'.eavFetchValue('title', $item->attributes).'</td>'
         .'<td>'.eavFetchValue('owner_name', $item->attributes).'</td>'
         .'<td>'.eavFetchValue('group_name', $item->attributes).'</td>'
         .'<td>'.eavFetchValue('created_at', $item->attributes).'</td>'
         .'<td>'.eavFetchValue('is_enabled', $item->attributes, 'htmlStatus').'</td>'
         .'<td>';
    $helper->a('page/list/edit/'.$item->entity_id, __('Edit'));
    print '</td></tr>';
  }
  print tableClose();
}
?>