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
      user_email: {
        required: true,
        remote: "<?php print uriSite('user/ajax/unique');?>?exclude=<?php print $view->user->getUser_id();?>",
        email: true
      },
      role_name: "required"
    },
    messages: {
      user_email: {
        required: "<?php print __('Please enter a email');?>",
        email: "<?php print __('Please enter a valid email');?>",
        remote: "<?php print __('Email is already used by another user');?>"
      },
      role_name: '<?php print __('Please choose role');?>'
    },
    errorClass: "-i-error"
  });
});
</script>
<?
$helper->heading(__('Users - Edit user'));
print formOpen(uriSite('/user/action/edit/'.$view->user->getUser_id()), array(
  'id'     => '-x-user-edit',
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
  'class' => '-i-text',
  'disabled' => true
  ), $view->user->getUser_name()
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
                ), $view->user->getUser_email()
);
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen(__('Choose role'), array(
  'id'          => '-x-field-role_name',
  'is_required' => true
));
print formDropdown('role_name', array_merge(array(''=>''), $view->roles_list), $view->user->getRole_key(), 'id="-x-input-role_name" class="-i-dropdown"');
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen(__('Description'), array(
  'id' => '-x-field-description'
));
print formTextarea(array(
                  'id'    => '-x-input-description',
                  'name'  => 'description',
                  'class' => '-i-textarea'
                ), $view->user->getDescription()
);
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen(__('Set new password'), array(
  'id' => '-x-field-password'
));
print formInput(array(
                  'id'    => '-x-input-password',
                  'name'  => 'password',
                  'class' => '-i-text'
                ), ''
);
print formFieldsetClose();
// -------------------------------------------------------------------------------
print formFieldsetOpen('', array(
  'id' => '-x-field-is_enabled'
));

print formCheckbox('is_enabled', '1', $view->user->getIs_enabled());
print formLabel(__('Activate this user?'));
print formFieldsetClose();
// -------------------------------------------------------------------------------
// Print submit
print formSubmit(array(
                   'class' => '-b-button',
                   'name'  => 'submit',
                   'value' => __('Save user')
                 ));
// Close form
print formClose();
?>