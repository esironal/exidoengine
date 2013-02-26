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
 * Debug class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    05/06/2011
 * @version    1.0
 */
final class Debug
{
  /**
   * Removes application, system, component path or docroot from a filename,
   * replacing them with the plain text equivalents. Useful for debugging
   * when you want to display a shorter path.
   * @param string $file
   * @return string
   */
  public static function path($file)
  {
    if(strpos($file, APPPATH) === 0) {
      $file = substr($file, strlen(APPPATH));
    }
    elseif(strpos($file, SYSPATH) === 0)
    {
      $file = substr($file, strlen(SYSPATH));
    }
    elseif(strpos($file, COMPATH) === 0)
    {
      $file = substr($file, strlen(COMPATH));
    }
    elseif(strpos($file, DOCROOT) === 0)
    {
      $file = substr($file, strlen(DOCROOT));
    }
    return $file;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a HTML string of information about a single variable.
   * Borrows heavily on concepts from the Debug class of [Nette](http://nettephp.com/).
   * @param string $value
   * @param int $length
   * @return string
   */
  public static function dump($value, $length = 128)
  {
    return Debug::_dump($value, $length);
  }

  // ---------------------------------------------------------------------------

  /**
   * Helper for Debug::dump(), handles recursion in arrays and objects.
   * @param string $var
   * @param int $length
   * @param int $level
   * @return string
   */
  protected static function _dump($var, $length = 128, $level = 0)
  {
    if($var === null) {
      return '<small>null</small>';
    }
    elseif(is_bool($var))
    {
      return '<small>bool</small> '.($var ? 'TRUE' : 'FALSE');
    }
    elseif(is_float($var))
    {
      return '<small>float</small> '.$var;
    }
    elseif(is_resource($var))
    {
      if(($type = get_resource_type($var)) === 'stream' and $meta = stream_get_meta_data($var)) {
        $meta = stream_get_meta_data($var);
        if(isset($meta['uri'])) {
          return '<small>resource</small><span>('.$type.')</span> '.htmlspecialchars($meta['uri'], ENT_NOQUOTES);
        }
      } else {
        return '<small>resource</small><span>('.$type.')</span>';
      }
    }
    elseif(is_string($var))
    {
      // Encode the string
      $str = htmlspecialchars($var, ENT_NOQUOTES);
      return '<small>string</small><span>('.strlen($var).')</span> "'.$str.'"';
    }
    elseif(is_array($var))
    {
      $output = array();

      // Indentation for this variable
      $space = str_repeat($s = '    ', $level);

      static $marker;

      if($marker === null) {
        // Make a unique marker
        $marker = uniqid("\x00");
      }

      if(empty($var)) {
        // Do nothing
      } elseif(isset($var[$marker])) {
        $output[] = "(".EXIDO_EOL."$space$s*RECURSION*".EXIDO_EOL."$space)";
      } elseif($level < 5) {
        $output[] = "<span>(";

        $var[$marker] = true;
        foreach($var as $key => & $val) {
          if($key === $marker) continue;
          if( ! is_int($key)) {
            $key = '"'.htmlspecialchars($key, ENT_NOQUOTES, __('__charset')).'"';
          }
          $output[] = "$space$s$key => ".Debug::_dump($val, $length, $level + 1);
        }
        unset($var[$marker]);
        $output[] = "$space)</span>";
      } else {
        // Depth too great
        $output[] = "(".EXIDO_EOL."$space$s...".EXIDO_EOL."$space)";
      }
      return '<small>array</small><span>('.count($var).')</span> '.implode(EXIDO_EOL, $output);
    }
    elseif(is_object($var))
    {
      // Copy the object as an array
      $array = (array) $var;

      $output = array();

      // Indentation for this variable
      $space = str_repeat($s = '    ', $level);

      $hash = spl_object_hash($var);

      // Objects that are being dumped
      static $objects = array();

      if(empty($var)) {
        // Do nothing
      }
      elseif(isset($objects[$hash]))
      {
        $output[] = "{".EXIDO_EOL."$space$s*RECURSION*".EXIDO_EOL."$space}";
      }
      elseif($level < 10)
      {
        $output[] = "<code>{";

        $objects[$hash] = true;
        foreach($array as $key => & $val) {
          if($key[0] === "\x00") {
            // Determine if the access is protected or protected
            $access = '<small>'.(($key[1] === '*') ? 'protected' : 'private').'</small>';
            // Remove the access level from the variable name
            $key = substr($key, strrpos($key, "\x00") + 1);
          } else {
            $access = '<small>public</small>';
          }
          $output[] = "$space$s$access $key => ".Debug::_dump($val, $length, $level + 1);
        }
        unset($objects[$hash]);
        $output[] = "$space}</code>";
      }
      else
      {
        // Depth too great
        $output[] = "{".EXIDO_EOL."$space$s...".EXIDO_EOL."$space}";
      }
      return '<small>object</small> <span>'.get_class($var).'('.count($array).')</span> '.implode(EXIDO_EOL, $output);
    }
    else
    {
      return '<small>'.gettype($var).'</small> '.htmlspecialchars(print_r($var, true), ENT_NOQUOTES);
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a HTML string, highlighting a specific line of a file, with some
   * number of lines padded above and below.
   *
   *     // Highlights the current line of the current file
   *     echo Debug::source(__FILE__, __LINE__);
   *
   * @param string $file
   * @param int $line_number
   * @param int $padding
   * @return bool|string
   */
  public static function source($file, $line_number, $padding = 5)
  {
    if( ! $file or ! is_readable($file)) {
      // Continuing will cause errors
      return false;
    }

    // Open the file and set the line position
    $file = fopen($file, 'r');
    $line = 0;

    // Set the reading range
    $range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);

    // Set the zero-padding amount for line numbers
    $format = '% '.strlen($range['end']).'d';

    $source = '';
    while(($row = fgets($file)) !== false) {
      // Increment the line number
      if(++$line > $range['end'])
        break;

      if($line >= $range['start']) {
        // Make the row safe for output
        $row = htmlspecialchars($row, ENT_NOQUOTES);

        // Trim whitespace and sanitize the row
        $row = '<span class="number">'.sprintf($format, $line).'</span> '.$row;

        if($line === $line_number) {
          // Apply highlighting to this row
          $row = '<span class="line highlight">'.$row.'</span>';
        } else {
          $row = '<span class="line">'.$row.'</span>';
        }
        // Add to the captured source
        $source.= $row;
      }
    }
    // Close the file
    fclose($file);
    return '<pre class="source"><code>'.$source.'</code></pre>';
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns an array of HTML strings that represent each step in the backtrace.
   *
   *     // Displays the entire current backtrace
   *     echo implode('<br/>', Debug::trace());
   *
   * @param array $trace
   * @return array
   */
  public static function trace(array $trace = null)
  {
    if($trace === null) {
      // Start a new trace
      $trace = debug_backtrace();
    }

    // Non-standard function calls
    $statements = array('include', 'include_once', 'require', 'require_once');

    $output = array();
    foreach($trace as $step) {
      if( ! isset($step['function'])) {
        // Invalid trace step
        continue;
      }

      if(isset($step['file']) and isset($step['line'])) {
        // Include the source of this step
        $source = Debug::source($step['file'], $step['line']);
      }

      if(isset($step['file'])) {
        $file = $step['file'];
        if(isset($step['line'])) {
          $line = $step['line'];
        }
      }

      // function()
      $function = $step['function'];

      if(in_array($step['function'], $statements)) {
        if(empty($step['args'])) {
          // No arguments
          $args = array();
        } else {
          // Sanitize the file path
          $args = array($step['args'][0]);
        }
      } elseif(isset($step['args'])) {
        if( ! function_exists($step['function']) or strpos($step['function'], '{closure}') !== false) {
          // Introspection on closures or language constructs in a stack trace is impossible
          $params = null;
        } else {
          if(isset($step['class'])) {
            if(method_exists($step['class'], $step['function'])) {
              $reflection = new ReflectionMethod($step['class'], $step['function']);
            } else {
              $reflection = new ReflectionMethod($step['class'], '__call');
            }
          } else {
            $reflection = new ReflectionFunction($step['function']);
          }

          // Get the function parameters
          $params = $reflection->getParameters();
        }

        $args = array();

        foreach($step['args'] as $i => $arg) {
          if(isset($params[$i])) {
            // Assign the argument by the parameter name
            $args[$params[$i]->name] = $arg;
          } else {
            // Assign the argument by number
            $args[$i] = $arg;
          }
        }
      }

      if(isset($step['class'])) {
        // Class->method() or Class::method()
        $function = $step['class'].$step['type'].$step['function'];
      }

      $output[]    = array(
        'function' => $function,
        'args'     => isset($args)   ? $args   : null,
        'file'     => isset($file)   ? $file   : null,
        'line'     => isset($line)   ? $line   : null,
        'source'   => isset($source) ? $source : null,
      );
      unset($function, $args, $file, $line, $source);
    }
    return $output;
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object.
   */
  final private function __construct()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object.
   */
  final private function __clone()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }
}

?>