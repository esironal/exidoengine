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
 * Fetch an attribute value. Call a user function given by the third parameter.
 * Class methods may also be invoked statically using this function by passing
 * array($classname, $methodname) to this parameter. Additionally class methods
 * of an object instance may be called by passing array($objectinstance, $methodname)
 * to this parameter.
 * If an attribute value will be found, it will be passed to the function/class method.
 * @param string $key
 * @param array $attributes
 * @param string $callback
 * @return string
 */
function eavFetchValue($key, array $attributes, $callback = null)
{
  if(isset($attributes[$key])) {
    if(null !== $callback and function_exists($callback)) {
      return call_user_func($callback, $attributes[$key]->value);
    }
    return $attributes[$key]->value;
  }
  return sprintf(__("Undefined attribute %s"), $key);
}

// ---------------------------------------------------------------------------

function eavCreateFormValidationJS(array $attributes)
{
  $script = '<script type="text/javascript">'.EXIDO_EOL;
  $script.= '$(function() {';
  $script.= "$('#-x-page-edit').validate({";
  $rules  = array();
  foreach($attributes as $field) {
    if($field->backend_object != null) {
      switch($field->data_type_key) {
        case 'text' :
          $rules[$field->attribute_key] = array(
            'required' => ((bool)$field->is_required) ? 'true' : 'false'
          );
          break;
        case 'varchar' :
          $rules[$field->attribute_key] = array(
            'required' => ((bool)$field->is_required) ? 'true' : 'false'
          );
          break;
        case 'int' :
          $rules[$field->attribute_key] = array(
            'required' => ((bool)$field->is_required) ? 'true' : 'false',
            'number'      => true
          );
          break;
        case 'decimal' :

          break;
        case 'datetime' :

          break;
        case 'bool' :
          $rules[$field->attribute_key] = array(
            'required' => ((bool)$field->is_required) ? 'true' : 'false'
          );
          break;
      }
    }
  }
  if( ! empty($rules)) {
    $script.= 'rules: {';
    $c_rule = count($rules);
    $c = 1;
    foreach($rules as $key => $rule) {
      $script.= $key.': {';
      $b_rule = count($rule);
      $b = 1;
      foreach($rule as $k => $v) {
        $script.= $k.': '.$v;
        if($b < $b_rule) $script.=',';
        $b++;
      }
      $script.= '}';
      if($c < $c_rule) $script.=',';
      $c++;
    }
    $script.= '}';
  }
  $script.= ', errorClass: "-i-error",';
  $script.= 'submitHandler: function(form) { form.submit(); }});';
  $script.= '});'.EXIDO_EOL;
  $script.= '</script>'.EXIDO_EOL;
  return $script;
}

// ---------------------------------------------------------------------------

function eavCreateForm($id, array $attributes, $action = '')
{
  print eavCreateFormValidationJS($attributes);
  if(empty($action))
    $action = uriFull();
  // Print open tag
  print formOpen($action, array(
    'id'     => $id,
    'class'  => '-i-form',
    'method' => 'POST'
  ));
  // Print form fields
  foreach($attributes as $field) {
    // Create form field
    if($field->backend_object != null) {
      // Parse helper name and function name
      $field_helper = explode('/', $field->backend_object, 2);
      if(isset($field_helper[0]) and isset($field_helper[1])) {
        // Load required helper
        Helper::load($field_helper[0]);
        if(function_exists($field_helper[1])) {
          // And execute the function
          print call_user_func($field_helper[1], $field);
        }
      }
    }
  }
  // Print submit
  print formSubmit(array(
                     'class' => '-b-button',
                     'name'  => 'submit',
                     'value' => __('Save')
                   ));
  // Close form
  print formClose();
}

// ---------------------------------------------------------------------------

function eavFormInput(Database_Mapper_Result $attributes)
{
  $output = formFieldsetOpen($attributes->description, array(
    'id'          => '-x-field-'.$attributes->attribute_key,
    'is_required' => $attributes->is_required
  ));
  $output.= formInput(array(
                    'id'    => '-x-input-'.$attributes->attribute_key,
                    'name'  => $attributes->attribute_key,
                    'class' => '-i-text'
                  ),((isset($attributes->value))?$attributes->value:''));
  $output.= formFieldsetClose();
  return $output;
}

// ---------------------------------------------------------------------------

function eavFormTextarea(Database_Mapper_Result $attributes)
{
  $output = formFieldsetOpen($attributes->description, array(
    'id'          => '-x-field-'.$attributes->attribute_key,
    'is_required' => $attributes->is_required
  ));
  $output.= formTextarea(array(
                       'id'    => '-x-input-'.$attributes->attribute_key,
                       'name'  => $attributes->attribute_key,
                       'class' => '-i-textarea'
                     ),((isset($attributes->value))?$attributes->value:''));
  $output.= formFieldsetClose();
  return $output;
}

// ---------------------------------------------------------------------------

function eavFormCheckbox(Database_Mapper_Result $attributes)
{
  $output = formFieldsetOpen('', array(
    'id' => '-x-field-'.$attributes->attribute_key
  ));

  $checked = false;
  if(isset($attributes->value) and $attributes->value == true)
    $checked = true;

  $output.= formCheckbox($attributes->attribute_key, '1', $checked);
  $is_required = (bool)(isset($attributes->is_required) and (bool)$attributes->is_required == true);
  $output.= formLabel($attributes->description.($is_required ? '<sup>*</sup>' : ''));
  $output.= formFieldsetClose();
  return $output;
}

?>