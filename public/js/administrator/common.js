/*******************************************************************************
 * ExidoEngine Administrator Javascript
 *
 * Copyright (c) 2009 - 2013, ExidoEngine Solutions
 * Licensed under GNU General Public License v3
 * http://www.exidoengine.com/license/gpl-3.0.html
 *******************************************************************************/

$(function(){

});

function ui_notification(text, css) {
  $('#notification')
    .removeClass('ui-popup-success')
    .removeClass('ui-popup-error')
    .html(text)
    .addClass(css)
    .fadeIn();
  setTimeout(function() {
    //$('#notification').fadeOut();
  }, 5000);
}