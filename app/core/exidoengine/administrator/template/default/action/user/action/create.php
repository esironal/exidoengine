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
  '/user/' => __('Go back')
);
// Include menu code
$view->getView('layout/inc.list-action-menu-panel');
?>
<script type="text/javascript">
$(function() {
  $.validator.addMethod("characters", function(value) {
    if (value.match(/^\w+$/i) != null) {
      return value;
    }
  }, "<?php print __('User name may contains only latin characters and numbers');?>");

  $('form#-x-user-add').validate({
    rules: {
      user_name: {
        required: true,
        remote: "<?php print uriSite('user/ajax/unique');?>",
        characters: true
      },
      user_email: {
        required: true,
        remote: "<?php print uriSite('user/ajax/unique');?>",
        email: true
      },
      role_name: "required"
    },
    messages: {
      user_name: {
        required: "<?php print __('Please enter a user name');?>",
        remote: "<?php print __('User name is already exists');?>"
      },
      user_email: {
        required: "<?php print __('Please enter a email');?>",
        email: "<?php print __('Please enter a valid email');?>",
        remote: "<?php print __('Email is already exists');?>"
      },
      role_name: '<?php print __('Please choose role');?>'
    },
    errorClass: "-i-error"
  });
});
</script>
<?
$helper->heading(__('Users - Create user'));
print formOpen(uriSite('/user/action/create'), array(
  'id'     => '-x-user-add',
  'class'  => '-i-form',
  'method' => 'POST'
));
// Form fields
print formFieldsetOpen(__('User name (login name)'), array(
  'id'          => '-x-field-user_name',
  'is_required' => true
));
print formInput(array(
  'id'    => '-x-input-user_name',
  'name'  => 'user_name',
  'class' => '-i-text'
  ), ''
);
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen(__('User email'), array(
  'id'          => '-x-field-user_email',
  'is_required' => true
));
print formInput(array(
                  'id'    => '-x-input-user_email',
                  'name'  => 'user_email',
                  'class' => '-i-text'
                ), ''
);
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen(__('Choose role'), array(
  'id'          => '-x-field-role_name',
  'is_required' => true
));
print formDropdown('role_name', array_merge(array(''=>''), $view->roles_list), '', 'id="-x-input-role_name" class="-i-dropdown"');
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen(__('Description'), array(
  'id' => '-x-field-description'
));
print formTextarea(array(
                  'id'    => '-x-input-description',
                  'name'  => 'description',
                  'class' => '-i-textarea'
                ), ''
);
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen(__('Set password'), array(
  'id' => '-x-field-password'
));
print formInput(array(
                  'id'    => '-x-input-password',
                  'name'  => 'password',
                  'class' => '-i-text'
                ), ''
);
print formHint('Note: leave empty to generate a random password automatically');
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen('', array(
  'id' => '-x-field-do_not_email_password'
));

print formCheckbox('do_not_email_password', '1', false);
print formLabel(__('Do not email the password'));
print formHint('Note: by default the password will be emailed, but you can prevent this by unchecking this option');
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen('', array(
  'id' => '-x-field-is_enabled'
));

print formCheckbox('is_enabled', '1', true);
print formLabel(__('Activate this user?'));
print formFieldsetClose();
// -------------------------------------------------------------------------------
// Print submit
print formSubmit(array(
                   'class' => '-b-button',
                   'name'  => 'submit',
                   'value' => __('Create user')
                 ));
// Close form
print formClose();
?>