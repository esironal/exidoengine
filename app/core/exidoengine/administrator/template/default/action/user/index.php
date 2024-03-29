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

// List actions menu
$view->action_menu = array(
  '/user/action/create' => __('Create user')
);
// Include menu code
$view->getView('layout/inc.list-action-menu-panel');

$helper->heading(__('Users'));

if($view->item_list) {
  print tableOpen('-i-table -i-table-striped');
  print tableHead(array(
                    __('ID'),
                    __('User name'),
                    __('Email'),
                    __('Owner'),
                    __('Group'),
                    __('Role'),
                    __('Joined at'),
                    __('Status'),
                    __('Actions')
  ));
  foreach($view->item_list as $item) {
    $item->is_enabled = htmlStatus($item->is_enabled);
    $item->created_at = dateConvertSQL2Human($item->created_at, Exido::config('global.date.format_long'));

    $item->actions = '<a href="user/action/edit/'.$item->user_id.'">'.__('Edit').'</a> ';
    $item->actions.= '<a class="remove" href="user/action/remove/'.$item->user_id.'">'.__('Remove').'</a>';
    print tableTR(arrayExtract((array)$item, array(
      'user_id', 'user_name', 'user_email',
      'owner_name', 'group_name', 'role_name',
      'created_at', 'is_enabled', 'actions'
    )));
  }
  print tableClose();
}

?>