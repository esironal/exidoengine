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

$form = '<script>$(function(){$("#welcome_dialog").dialog({height:140,modal:true,autoOpen:false,open:function(event,ui){$("button").attr("title","'.__('Close').'");}});$("form").validate({rules:{username:"required",password:"required"},messages:{username:"'.__('Please enter a username').'",password:"'.__('Please enter a password').'"},submitHandler:function(form){$.post("'.uriSite('auth').'",{uid:$("input[name=username]").val(),pwd:$("input[name=password]").val()},function(data){if(data.status == true){
      $("#welcome_dialog p").html(data.text);
      $("#welcome_dialog").dialog("open");
      setTimeout(function(){window.location.reload();},1000);
    } else {
      alert(data.text);
      form.reset();
    }},"json");}});});</script>'
       .'<form class="-i-form" method="POST" action="">'
       .'<fieldset>'
       .'<legend>'.__('Username').'</legend><input class="-i-text" type="text" name="username" value="exidoengine" maxlength="32" autocomplete="off" /><label for="username" generated="true" class="error">&nbsp;</label>'
       .'</fieldset>'
       .'<fieldset>'
       .'<legend>'.__('Password').'</legend><input class="-i-text" type="password" name="password" value="exidoengine" /><label for="password" generated="true" class="error">&nbsp;</label>'
       .'</fieldset>'
       .'<fieldset>'
       .'<input class="-b-button" type="submit" name="submit" value="'.__('Sign in').'" />'
       .'</fieldset>'
       .'</form>';
$msg1 = '<span>'.__('Welcome to administration panel').'</span>'
       .'<span>'.__('Please authorize your person').'</span>';
$msg2 = '<span>'.__('Please enter your credentials in the box above.').'</span>'
       .'<span>'.__('You have 3 attempts to authorize yourself.').'</span>'
       .'<span>'.__('After third incorrect attempt, you will be rejected for a some time.').'</span>';
$dialogs = '<div id="welcome_dialog" title="'.__('Welcome').'">'
          .'<p></p>'
          .'</div>';
$helper
  ->doctype()
  ->openHtml()
  ->openHead()
  ->base()
  ->title(__('ExidoEngine administration panel'))
  ->charset()
  ->fav('exidoengine')
  ->css('exido-bootstrap/bootstrap')
  ->css('exido-bootstrap/bootstrap-green')
  ->css('administrator/signin-form')
  ->css('administrator/jqueryui')
  ->js('administrator/jquery')
  ->js('administrator/jqueryui')
  ->js('administrator/form.validate')
  ->js('administrator/common')
  ->closeHead()
  ->openBody()
  ->open('wrapper', 'wrapper')
  ->open('container')
  ->open('form-text')
  ->notifier($msg1, 'msg1 messages')
  ->notifier($form, 'signin-form')
  ->notifier($msg2, 'msg2 messages');
print $dialogs;
$helper
  ->close()
  ->close()
  ->close()
  ->notifier('<a href="http://www.exidoengine.com" target="_blank">ExidoEngine Web-sites manager</a>', 'footer')
  ->closeBody()
  ->closeHtml();
?>