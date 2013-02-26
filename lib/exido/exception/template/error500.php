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

$helper
  ->openHtml()
  ->openHead()
  ->base()
  ->title(__('Error 500').' &mdash; '.__('Internal server error'))
  ->charset()
  ->fav('exidoengine')
  ->style('body{background-color:#fff;margin:0;padding:0;font:14px Arial,Helvetica,sans-serif;}
    #sidebar{float:right;height: 100%; width: 35%;background-color: #242424;position:relative;}
    .logo{height:36px;width:247px;border:0;top:40%;left:50%;margin-left:-123px;position:absolute;}
    h1,h2,h3{margin:0;padding:0;font-weight:normal;text-align:center;}
    h1{font:4em Arial, Helvetica, sans-serif}
    h3{font-size:2.2em;}
    .message{padding-top:250px;}')
  ->closeHead()
  ->openBody()
  ->open('sidebar', '')
  ->notifier('<img alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPoAAAApCAYAAAAcRjS8AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpGNDkwQzYxRjU2NTBFMDExOUE1QUY2QjNFOUQzNUQ1NyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoyNzQxM0ZEMDI4RkMxMUUyOTI4MUEyQUIzRkNCODJFOSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoyNzQxM0ZDRjI4RkMxMUUyOTI4MUEyQUIzRkNCODJFOSIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1LjEgTWFjaW50b3NoIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6Rjg3RjExNzQwNzIwNjgxMTk4OEQ5ODEwNENDNzAzMjQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjQ5MEM2MUY1NjUwRTAxMTlBNUFGNkIzRTlEMzVENTciLz4gPC9yZGY6RGVzY3Jp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" />', 'logo')
  ->close()
  ->notifier('<h1>'.__('Error 500').'</h1><h3>'.$view->message.'</h3>', 'message')
  ->closeBody()
  ->closeHtml();
?>