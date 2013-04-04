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
 * Form validation class
 * @package    core
 * @subpackage validation
 * @copyright  Sharapov A.
 * @created    16/02/2010
 * @version    1.0
 */
class Validation_Form
{
  public $rules  = array();
  public $fields = array();
  public $errors = array();
  public $rules_messages  = array();
  public $custom_errors   = array();
  public $required_fields = array();
  public $protocol        = 'post';
  public $custom_input    = array();

  private $_has_run = false;

  // -----------------------------------------------------------------------------

  /**
   * Constructor. Sets the custom input array.
   * @param null $input
   */
  public function __construct($input = null)
  {
    if( ! is_array($input)) {
      $input = array();
    }
    $this->useCustomInput($input);
  }

  // -----------------------------------------------------------------------------

  /**
   * Sets to use $_POST protocol.
   * @return void
   */
  public function usePost()
  {
    $this->protocol = 'post';
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets to use $_GET protocol.
   * @return void
   */
  public function useGet()
  {
    $this->protocol = 'get';
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets to use $_GET protocol.
   * @param array $input
   */
  public function useCustomInput(array $input)
  {
    $this->custom_input = $input;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets a validation rule.
   * @param string $field
   * @param string $rule
   * @return bool
   */
  public function setRule($field, $rule)
  {
    if(empty($rule) or empty($field)) {
      return false;
    }
    $this->rules[$field] = $rule;
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets the error message for validation rule.
   * @param string $field
   * @param string $text
   * @param string $rule
   * @return bool
   */
  public function setRuleError($field, $text, $rule)
  {
    if(empty($rule) or empty($field)) {
      return false;
    }
    $this->rules_messages[$field][$rule] = $text;
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets a custom error text.
   * @param string $text
   * @param string $key
   * @return void
   */
  public function setCustomError($text, $key = '')
  {
    if(is_array($text)) {
      foreach($text as $k => $v) {
        $this->custom_errors[$k] = $v;
      }
    } else {
      if($key == '') {
        $this->custom_errors[] = $text;
      } else {
        $this->custom_errors[$key] = $text;
      }
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets a field label.
   * @param string $field
   * @param string $label
   * @return bool
   */
  public function setFieldLabel($field, $label)
  {
    if(empty($label) or empty($field)) {
      return false;
    }
    $this->fields[$field] = $label;
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Runs a validation rules.
   * @return bool
   */
  public function run()
  {
    if(empty($this->rules) and empty($this->custom_errors)) {
      return true;
    }

    if(empty($this->custom_input)) {
      // Get field value
      if($this->protocol == 'get') {
        $input = $_GET;
      } else {
        $input = $_POST;
      }
    } else {
      $input = $this->custom_input;
    }

    // We cannot continue validation if input array is empty
    if(empty($input)) {
      $this->_has_run = false;
      return false;
    }

    foreach($this->rules as $field => $rules) {
      // Get field label text if exists
      $field_label = $this->_getFieldLabel($field);
      if( ! isset($input[$field])) {
        $input[$field] = '';
      }
      if(is_array($input[$field])) {
        $input_value = $input[$field];
      } else {
        $input_value = trim($input[$field]);
      }

      // Explode rules
      $e = explode('|', $rules);

      foreach($e as $rule) {
        if($input_value === '' and $rule != 'required') {
          continue;
        }
        // Validate a custom rule
        if(preg_match('/^custom\[(.*)\]$/', $rule, $m) and count($m) == 2) {
          if( ! $this->custom($input_value, $m[1])) {
            if($msg = $this->_getErrorMessage($field, 'custom')) {
              $this->errors[$field] = $msg;
            } else {
              $this->errors[$field] = sprintf(__('Field %s contains an incorrect data'), $field_label);
            }
          }
          // Validate using a callback method
        } elseif(preg_match('/^callfunc\[([A-z0-9_-]+)\]$/', $rule, $m) and count($m) == 2) {
          // Check if the method exists
          if( ! function_exists($m[1])) {
            $this->errors[$field] = sprintf(__('Undefined callback function %s'), $m[1]);
          } else {
            // Execute a callback method
            if( ! call_user_func($m[1], $input_value)) {
              if($msg = $this->_getErrorMessage($field, 'callfunc')) {
                $this->errors[$field] = $msg;
              } else {
                $this->errors[$field] = sprintf(__('Field %s contains an incorrect data'), $field_label);
              }
            }
          }
        } elseif(preg_match('/^([a-z]+)\[([A-z0-9_-]+)\]$/', $rule, $m) and count($m) > 2) {
          // Check the field value with each rule
          $tgt_field_name = $m[2];
          // Additional verification rules
          if($m[1] == 'consists') {
            $tgt_field_name = $this->_getFieldLabel($m[2]);
            if(isset($input[$m[2]])) {
              $m[2] = $input[$m[2]];
            } else {
              $m[2] = '';
            }
          }
          if( ! $this->$m[1]($input_value, $m[2])) {
            if($msg = $this->_getErrorMessage($field, $m[1])) {
              $this->errors[$field] = $msg;
            } else {
              $this->errors[$field] = sprintf(__('Incorrect data. Input params: %s, %s, %s'), $m[1], $field_label, $tgt_field_name);
            }
          }
        } else {
          // Check field value with each rule
          if( ! $this->$rule($input_value)) {
            if($msg = $this->_getErrorMessage($field, $rule)) {
              $this->errors[$field] = $msg;
            } else {
              $this->errors[$field] = sprintf(__('Incorrect data. Input params: %s, %s'), $rule, $field_label);
            }
          }
        }
      }
    }

    // Set the run trigger
    $this->_has_run = true;
    if(empty($this->errors) and empty($this->custom_errors)) {
      return true;
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Validates an Email.
   * @param string $val
   * @return bool
   */
  public function email($val)
  {
    return (bool)preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $val);
  }

  // ---------------------------------------------------------------------------

  /**
   * Custom validation by the regular expression.
   * @param string $val
   * @param string $pattern
   * @return bool
   */
  public function custom($val, $pattern)
  {
    return (bool)preg_match($pattern, $val);
  }

  // ---------------------------------------------------------------------------

  /**
   * Alpha validation.
   * @param string $val
   * @return bool
   */
  public function alpha($val)
  {
    return (bool)preg_match("/^([A-z])+$/i", $val);
  }

  // ---------------------------------------------------------------------------

  /**
   * Alpha-numeric validation.
   * @param string $val
   * @return bool
   */
  public function alphaNum($val)
  {
    return (bool)preg_match("/^([A-z0-9])+$/i", $val);
  }

  // ---------------------------------------------------------------------------

  /**
   * Alpha-numeric with underscores and dashes.
   * @param string $val
   * @return bool
   */
  public function alphaDash($val)
  {
    return (bool)preg_match("/^([A-z0-9_-])+$/i", $val);
  }

  // ---------------------------------------------------------------------------

  /**
   * Checks a maximum length.
   * @param string $val
   * @param int $length
   * @return bool
   */
  public function max($val, $length)
  {
    if(preg_match("/[^0-9]/", $length)) {
      return false;
    }

    if(function_exists('mb_strlen')) {
      return (mb_strlen($val) <= $length) ? true : false;
    }

    return (strlen($val) <= $length) ? true : false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Checks a minimum length.
   * @param string $val
   * @param int $length
   * @return bool
   */
  public function min($val, $length)
  {
    if(preg_match("/[^0-9]/", $length)) {
      return false;
    }

    if(function_exists('mb_strlen')) {
      return (mb_strlen($val) >= $length) ? true : false;
    }

    return (strlen($val) >= $length) ? true : false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Checks exact length.
   * @param string $val
   * @param int $length
   * @return bool
   */
  public function exact($val, $length)
  {
    if(preg_match("/[^0-9]/", $length)) {
      return false;
    }

    if(function_exists('mb_strlen')) {
      return (mb_strlen($val) == $length) ? true : false;
    }

    return (strlen($val) == $length) ? true : false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Checks if the field is empty.
   * @param string $val
   * @return bool
   */
  public function required($val)
  {
    return ($val === '') ? false : true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Checks number.
   * @param int $val
   * @return bool
   */
  public function num($val)
  {
    return (bool)is_numeric($val);
  }

  // ---------------------------------------------------------------------------

  /**
   * Comparison of two non-empty strings.
   * @param string $str
   * @param string $str2
   * @return bool
   */
  public function consists($str, $str2)
  {
    $str  = trim($str);
    $str2 = trim($str2);
    if(empty($str) or empty($str2)) {
      return false;
    }
    return ($str == $str2) ? true : false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Checks if the entered captcha string consists with the session captcha string.
   * @param string $str
   * @return bool
   */
  public function captcha($str)
  {
    // Get secret word from session
    $s    = Registry::factory('Session');
    $str2 = $s->get('captcha');
    if(empty($str) or empty($str2)) {
      return false;
    }
    return ($str == $str2) ? true : false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an error message for specified field.
   * @param string $field
   * @return string
   */
  public function getError($field)
  {
    if(isset($this->errors[$field]) and $this->_has_run) {
      return $this->errors[$field];
    }
    return '';
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an errors array.
   * @return array
   */
  public function getErrors()
  {
    return ($this->_has_run) ? array_merge($this->errors, $this->custom_errors) : array();
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns TRUE if at least one error exists.
   * @return bool
   */
  public function isErrors()
  {
    return (count($this->getErrors()) == 0) ? false : true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Replace an entites.
   * @param string $subject
   * @param array $vars
   * @return string
   */
  private function _replaceText($subject, array $vars)
  {
    return strtr($subject, $vars);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets an error message.
   * @param string $field
   * @param string $rule
   * @return bool
   */
  private function _getErrorMessage($field, $rule)
  {
    if(empty($rule) or empty($field)) {
      return false;
    }
    return (isset($this->rules_messages[$field][$rule])) ? $this->rules_messages[$field][$rule] : false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a field label.
   * @param string $field
   * @return string
   */
  private function _getFieldLabel($field)
  {
    if(empty($field)) {
      return $field;
    }
    return (isset($this->fields[$field])) ? $this->fields[$field] : $field;
  }

  // ---------------------------------------------------------------------------

  /**
   * Handles methods that do not exist.
   * @param string $method
   * @param array $args
   * @return void
   * @throws Exception_Exido
   */
  public function __call($method, array $args)
  {
    throw new Exception_Exido('Undefined method %s(%s)', array($method, implode(', ', $args)));
  }
}

?>