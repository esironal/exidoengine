<?php defined('SYSPATH') or die('No direct script access allowed.');

/*******************************************************************************
 * ExidoEngine Content Management System
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (3.0)
 * that is bundled with this package in the file license_en.txt
 * It is also available through the world-wide-web at this URL:
 * http://exidoengine.com/license/gpl-3.0.html
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
 * @license   http://exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

$form = '<script>$(function(){$("form").validate({rules:{username:"required",password:"required"},messages:{username:"Please enter a username",password:"Please enter a password"},submitHandler:function(form){$.post("/index.php/auth",{uid: $("input[name=username]").val(), pwd: $("input[name=password]").val()}, function(data){if(data.status == true && data.text == "AUTH"){
      alert("'.__('Thank you').'");
      window.location.reload();
    } else {
      alert(data.text);
      form.reset();
    }},"json");}});});</script>'
       .'<form class="-i-form" method="POST" action="">'
       .'<fieldset>'
       .'<legend>'.__('Username').'</legend><input class="-i-text" type="text" name="username" value="" maxlength="32" autocomplete="off" />'
       .'</fieldset>'
       .'<fieldset>'
       .'<legend>'.__('Password').'</legend><input class="-i-text" type="password" name="password" value="" />'
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
  ->js('administrator/jquery')
  ->js('administrator/form.validate')
  ->closeHead()
  ->openBody()
  ->open('wrapper', 'wrapper')
  ->open('container')
  ->open('form-text')
  ->notifier($msg1, 'msg1 messages')
  ->notifier($form, 'signin-form')
  ->notifier($msg2, 'msg2 messages')
  ->close()
  ->close()
  ->close()
  ->notifier('<a href="http://www.exidoengine.com" target="_blank">ExidoEngine Content Management System</a>', 'footer')
  ->closeBody()
  ->closeHtml();
/*

<div id="horizon">
			<div id="content">
				<div class="bodytext"><div id="caption1" class="captions">The
cyan box 'horizon' is positioned absolutely 50% from the top of the
page, is 100% wide and has a nominal height of 1px. Its overflow is set
to 'visible'.</div>
					This text is<br>
					<span class="headline">DEAD CENTRE</span><br>
					and stays there!
					<div id="caption2" class="captions">The
red 'content' box is nested inside the 'horizon' box and is 250px wide,
70px high and is positioned absolutely 50% from the left - but has a
negative margin that is exactly half of its width, -125px. To get it to
centre vertically, it has a negative top position that is exactly half
of its height, -35px.</div>
				</div>
			</div>
		</div>
<div id="footer">
<a href="http://www.exidoengine.com" target="_blank">ExidoEngine Content Management System</a>
</div>*/
?>