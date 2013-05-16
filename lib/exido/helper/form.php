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

/**
 * Returns a div with a message. Using for input hints.
 * @param string $hint
 * @param string $class
 * @return string
 */
function formHint($hint, $class = 'form-field-hint')
{
  return '<div class="'.$class.'">'.$hint.'</div>';
}

// -------------------------------------------------------------------------------

/**
 * Returns a redirect button.
 * @param string $title
 * @param string $url
 * @param string $name
 * @return string
 */
function formRedirect($title, $url, $name = 'back')
{
  return formButton($name, $title, "onClick=\"location.href('".$url."')\"");
}

// -------------------------------------------------------------------------------

/**
 * Returns an error list
 * @param array $errors
 * @param string $class
 * @return string
 */
function formErrorList(array $errors, $class = 'form-list-error')
{
  if(empty($errors)) {
    return '';
  }
  $output = '<div class="'.$class.'"><ul>';
  foreach($errors as $error) {
    $output.= "<li>".$error."</li>";
  }
  $output.= "</ul></div>".EXIDO_EOL;
  return $output;
}

// -------------------------------------------------------------------------------

/**
 * Form declaration. Creates the opening portion of the form.
 * @param string $action
 * @param array $attributes
 * @return string
 */
function formOpen($action = '', array $attributes = array())
{
  if(empty($attributes)) {
    $attributes['method'] = "post";
  }
  $form = '<form action="'.$action.'"';
  $form.= formConvertAttrib2String($attributes, true);
  $form.= ">".EXIDO_EOL;
  return $form;
}

// -------------------------------------------------------------------------------

/**
 * Form declaration - Multipart type
 * Creates the opening portion of the form, but with "multipart/form-data".
 * @param string $action
 * @param array $attributes
 * @return string
 */
function formOpenMultipart($action = '', array $attributes = array())
{
  $attributes['enctype'] = 'multipart/form-data';
  return formOpen($action, $attributes);
}

// -------------------------------------------------------------------------------

/**
 * Form close tag.
 * @param string $extra
 * @return string
 */
function formClose($extra = '')
{
  return "</form>".$extra."".EXIDO_EOL;
}

// -------------------------------------------------------------------------------

/**
 * Hidden input field. Generates hidden fields. You can pass a simple key/value
 * string or an associative array with multiple values.
 * @param string $name
 * @param string $value
 * @param string $extra
 * @return string
 */
function formHidden($name, $value = '', $extra = '')
{
  $form = '';
  if(is_array($name)) {
    foreach($name as $key => $val) {
      formHidden($key, $val);
    }
    return $form;
  }

  if( ! is_array($value)) {
    if($extra != '') $extra = ' '.$extra;
    $form.= '<input type="hidden" name="'.$name.'" value="'.formPrepareField($value, $name).'"'.$extra.'/>'."".EXIDO_EOL;
  } else {
    foreach($value as $k => $v) {
      $k = (is_numeric($k)) ? '' : $k;
      formHidden($name.'['.$k.']', $v);
    }
  }
  return $form;
}

// -------------------------------------------------------------------------------

/**
 * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
 * @param string $str
 * @param string $field_name
 * @return array|mixed
 */
function formPrepareField($str, $field_name = '')
{
  static $prepped_fields = array();
  // If the field name is an array we do this recursively
  if(is_array($str)) {
    foreach($str as $key => $val) {
      $str[$key] = formPrepareField($val);
    }
    return $str;
  }
  // We've already prepped a field with this name
  // @TODO need to figure out a way to namespace this so
  // that we know the *exact* field and not just one with
  // the same name
  if(isset($prepped_fields[$field_name])) {
    return $str;
  }
  $str = htmlspecialchars($str);
  // In case htmlspecialchars misses these.
  $str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);
  if($field_name != '') {
    $prepped_fields[$field_name] = $str;
  }
  return $str;
}

// -------------------------------------------------------------------------------

/**
 * Text input field.
 * @param string $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function formInput($data = '', $value = '', $extra = '')
{
  $defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
  return "<input ".formParseAttributes($data, $defaults).$extra." />";
}

// -------------------------------------------------------------------------------

/**
 * Password field. Identical to the input function but adds the "password" type.
 * @param string $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function formPassword($data = '', $value = '', $extra = '')
{
  if( ! is_array($data)) {
    $data = array('name' => $data);
  }
  $data['type'] = 'password';
  return formInput($data, $value, $extra);
}

// -------------------------------------------------------------------------------

/**
 * Upload field. Identical to the input function but adds the "file" type.
 * @param string $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function formUpload($data = '', $value = '', $extra = '')
{
  if( ! is_array($data)) {
    $data = array('name' => $data);
  }
  $data['type'] = 'file';
  return formInput($data, $value, $extra);
}

// -------------------------------------------------------------------------------

/**
 * Textarea field.
 * @param string $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function formTextarea($data = '', $value = '', $extra = '')
{
  $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '90', 'rows' => '12');
  if( ! is_array($data) or ! isset($data['value'])) {
    $val = $value;
  } else {
    $val = $data['value'];
    unset($data['value']); // textareas don't use the value attribute
  }
  $name = (is_array($data)) ? $data['name'] : $data;
  return "<textarea ".formParseAttributes($data, $defaults).$extra.">".formPrepareField($val, $name)."</textarea>";
}

// ---------------------------------------------------------------------------

/**
 * Drop-down list.
 * @param string $name
 * @param array $options
 * @param array $selected
 * @param string $extra
 * @return string
 */
function formDropdown($name = '', array $options = array(), $selected = array(), $extra = '')
{
  if( ! is_array($selected)) {
    $selected = array($selected);
  }

  // If no selected state was submitted we will attempt to set it automatically
  if(count($selected) === 0) {
    // If the form name appears in the $_POST array we have a winner!
    if(isset($_POST[$name])) {
      $selected = array($_POST[$name]);
    }
  }

  if($extra != '') $extra = ' '.$extra;

  $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === false) ? ' multiple="multiple"' : '';

  $form = '<select name="'.$name.'"'.$extra.$multiple.">".EXIDO_EOL;

  foreach($options as $key => $val) {
    $key = (string) $key;
    if(is_array($val)) {
      $form.= '<optgroup label="'.$key.'">'.EXIDO_EOL;
      foreach($val as $optgroup_key => $optgroup_val) {
        $sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
        $form.= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>".EXIDO_EOL;
      }
      $form.= '</optgroup>'.EXIDO_EOL;
    } else {
      $sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
      $form.= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>".EXIDO_EOL;
    }
  }
  $form.= '</select>';
  return $form;
}

// -------------------------------------------------------------------------------

/**
 * Multi-select menu.
 * @param string $name
 * @param array $options
 * @param array $selected
 * @param string $extra
 * @return string
 */
function formMultiselect($name = '', array $options = array(), $selected = array(), $extra = '')
{
  if( ! strpos($extra, 'multiple')) {
    $extra.= ' multiple="multiple"';
  }
  return formDropdown($name, $options, $selected, $extra);
}

// -------------------------------------------------------------------------------

/**
 * Checkbox field.
 * @param string $data
 * @param string $value
 * @param bool $checked
 * @param string $extra
 * @return string
 */
function formCheckbox($data = '', $value = '', $checked = false, $extra = '')
{
  $defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

  if(is_array($data) and array_key_exists('checked', $data)) {
    $checked = $data['checked'];
    if($checked == false) {
      unset($data['checked']);
    } else {
      $data['checked'] = 'checked';
    }
  }
  if($checked == true) {
    $defaults['checked'] = 'checked';
  } else {
    unset($defaults['checked']);
  }
  return "<input ".formParseAttributes($data, $defaults).$extra." />";
}

// -------------------------------------------------------------------------------

/**
 * Radio button.
 * @param string $data
 * @param string $value
 * @param bool $checked
 * @param string $extra
 * @return string
 */
function formRadio($data = '', $value = '', $checked = false, $extra = '')
{
  if( ! is_array($data)) {
    $data = array('name' => $data);
  }
  $data['type'] = 'radio';
  return formCheckbox($data, $value, $checked, $extra);
}

// -------------------------------------------------------------------------------

/**
 * Submit button.
 * @param string $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function formSubmit($data = '', $value = '', $extra = '')
{
  $defaults = array('type' => 'submit', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
  return "<input ".formParseAttributes($data, $defaults).$extra." />".EXIDO_EOL;
}

// -------------------------------------------------------------------------------

/**
 * Reset button.
 * @param string $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function formReset($data = '', $value = '', $extra = '')
{
  $defaults = array('type' => 'reset', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
  return "<input ".formParseAttributes($data, $defaults).$extra." />".EXIDO_EOL;
}

// -------------------------------------------------------------------------------

/**
 * Form button.
 * @param string $data
 * @param string $content
 * @param string $extra
 * @return string
 */
function formButton($data = '', $content = '', $extra = '')
{
  $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'type' => 'button');
  if( is_array($data) and isset($data['content'])) {
    $content = $data['content'];
    unset($data['content']); // Content is not an attribute
  }
  return "<button ".formParseAttributes($data, $defaults).$extra.">".$content."</button>".EXIDO_EOL;
}

// -------------------------------------------------------------------------------

/**
 * Field label tag.
 * @param $label_text
 * @param string $id
 * @param array $attributes
 * @return string
 */
function formLabel($label_text, $id = '', array $attributes = array())
{
  $label = '<label';
  if( ! empty($id)) {
    $label.= ' for="'.$id.'"';
  }
  if( ! empty($attributes)) {
    foreach ($attributes as $key => $val) {
      $label.= ' '.$key.'="'.$val.'"';
    }
  }
  $label.= ">".$label_text."</label>".EXIDO_EOL;
  return $label;
}

// -------------------------------------------------------------------------------

/**
 * Fieldset tag. Uses to produce <fieldset><legend>text</legend>. To close fieldset
 * use fieldsetClose()
 * @param string $legend_text
 * @param array $attributes
 * @return string
 */
function formFieldsetOpen($legend_text = '', array $attributes = array())
{
  $is_required = (bool)(isset($attributes['is_required']) and (bool)$attributes['is_required'] == true);
  unset($attributes['is_required']);
  $fieldset = "<fieldset";
  $fieldset.= formConvertAttrib2StringFieldset($attributes);
  $fieldset.= ">".EXIDO_EOL;
  if( ! empty($legend_text)) {
    $fieldset.= "<legend>".$legend_text.($is_required ? '<sup>*</sup>' : '')."</legend>".EXIDO_EOL;
  } elseif($is_required) {
    $fieldset.= "<legend>".__('Required field')."</legend>".EXIDO_EOL;
  } else {}
  return $fieldset;
}

// -------------------------------------------------------------------------------

/**
 * Fieldset close tag.
 * @param string $extra
 * @return string
 */
function formFieldsetClose($extra = '')
{
  return "</fieldset>".$extra;
}

// -------------------------------------------------------------------------------

/**
 * Parse the form attributes.
 * @param mixed $attributes
 * @param array $default
 * @return string
 */
function formParseAttributes($attributes, array $default)
{
  if(is_array($attributes)) {
    foreach($default as $key => $val) {
      if(isset($attributes[$key])) {
        $default[$key] = $attributes[$key];
        unset($attributes[$key]);
      }
    }
    if(count($attributes) > 0) {
      $default = array_merge($default, $attributes);
    }
  }

  $atts = '';
  foreach($default as $key => $val) {
    if($key == 'value') {
      $val = formPrepareField($val, $default['name']);
    }
    $atts.= $key.'="'.$val.'" ';
  }
  return $atts;
}

// -------------------------------------------------------------------------------

/**
 * Convert attributes to string. Helper function used by some of the form helpers.
 * @param array $attributes
 * @return string
 */
function formConvertAttrib2String(array $attributes)
{
  $atts = '';
  if( ! empty($attributes)) {
    if( ! isset($attributes['method'])) {
      $atts.= ' method="post"';
    }
    foreach ($attributes as $key => $val) {
      $atts.= ' '.$key.'="'.$val.'"';
    }
  }
  return $atts;
}

// -------------------------------------------------------------------------------

/**
 * Convert attributes to string. Helper function used by some of the form helpers.
 * @param array $attributes
 * @return string
 */
function formConvertAttrib2StringFieldset(array $attributes)
{
  $atts = '';
  if( ! empty($attributes)) {
    foreach ($attributes as $key => $val) {
      $atts.= ' '.$key.'="'.$val.'"';
    }
  }
  return $atts;
}

// -------------------------------------------------------------------------------

/**
 * Set the field value.
 * @param string $key
 * @param string $default
 * @return string
 */
function setValue($key, $default = '')
{
  if(isset($_POST) and ! empty($_POST)) {
    $input = $_POST;
  } elseif(isset($_GET) and ! empty($_GET)) {
    $input = $_GET;
  } else {
    return $default;
  }

  if( ! isset($input[$key])) {
    return $default;
  }
  return $input[$key];
}

// -------------------------------------------------------------------------------

/**
 * Set the checkbox value. Checked or unchecked.
 * @param string $key
 * @param string $value
 * @param bool $checked
 * @return bool
 */
function setCheckbox($key, $value, $checked = false)
{
  if($checked) {
    return true;
  }
  if(isset($_POST) and ! empty($_POST)) {
    $input = $_POST;
  } elseif(isset($_GET) and ! empty($_GET)) {
    $input = $_GET;
  } else {
    return false;
  }

  if(isset($input[$key]) and $input[$key] == $value) {
    return true;
  }
  return false;
}

// -------------------------------------------------------------------------------

/**
 * Set the radio value.
 * @param string $key
 * @param string $value
 * @param bool $checked
 * @return bool
 */
function setRadio($key, $value, $checked = false)
{
  if($checked) {
    return true;
  }

  if(isset($_POST) and ! empty($_POST)) {
    $input = $_POST;
  } elseif(isset($_GET) and ! empty($_GET)) {
    $input = $_GET;
  } else {
    return false;
  }

  if(isset($input[$key]) and $input[$key] == $value) {
    return true;
  }
  return false;
}

?>