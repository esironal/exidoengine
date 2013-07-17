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
 * Returns a fav icon tag.
 * @param string $file
 * @param string $folder
 * @return string
 */
function htmlFavIcon($file = 'favicon', $folder = 'css/images')
{
  return '<link rel="shortcut icon" href="'.exido_fix_path($folder).$file.'.ico" />'.EXIDO_EOL
        .'<link rel="icon" href="'.exido_fix_path($folder).$file.'.ico" />'.EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Returns a meta description tag.
 * @param string $text
 * @return string
 */
function htmlMetaDescription($text)
{
  return '<meta name="description" content="'.$text.'" />'.EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Returns a meta keywords tag.
 * @param string $text
 * @return string
 */
function htmlMetaKeywords($text)
{
  return '<meta name="keywords" content="'.$text.'" />'.EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Returns an IE conditional comment string.
 * @param string $code
 * @param string $version
 * @return string
 */
function htmlIe($code, $version = '*')
{
  $html = '<!--[if ';
  switch($version) {
    case '6' :
      $html.= 'IE6';
      break;
    case '7' :
      $html.= 'IE7';
      break;
    case '8' :
      $html.= 'IE8';
      break;
    case '9' :
      $html.= 'IE9';
      break;
    case '*' :
    default:
      $html.= 'IE';
  }
  return $html."]>".EXIDO_EOL.$code."<![endif]-->".EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Returns a meta charset tag.
 * @param string $charset
 * @return string
 */
function htmlMetaCharset($charset = '')
{
  // Load the required helpers
  if(empty($charset)) {
    $charset = __('__charset');
  }
  return '<meta charset="'.$charset.'" />'.EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Returns an title tag.
 * @param string $title
 * @return string
 */
function htmlTitle($title)
{
  return '<title>'.$title."</title>".EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Returns an A tag.
 * @param string $url
 * @param string $title
 * @param string $class
 * @return string
 */
function htmlA($url, $title, $class = '')
{
  Helper::load('uri');
  return '<a'.((empty($class))?'':' class="'.$class.'"').' href="'.uriSite($url).'">'.$title."</a>".EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Prints or returns a HTML javascript include tag. First param is the file name.
 * Second param is the directory where javascript file is located.
 * If the first param is an array, function prints all an array elements
 * as javascript include tags.
 * @param string $js
 * @param string $folder
 * @return string
 */
function htmlJS($js, $folder = '')
{
  if(is_array($js)) {
    foreach($js as $file => $folder) {
      print htmlJS($file, $folder);
    }
    return '';
  }

  if(empty($folder)) {
    return '';
  }
  return '<script src="'.exido_fix_path($folder).$js.'.js"></script>'.EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Prints or returns a HTML css include tag. First param is the file name.
 * Second param is the directory where css file is located.
 * If the first param is an array, function prints all an array elements
 * as javascript include tags.
 * @param string $css
 * @param string $folder
 * @return string
 */
function htmlCSS($css, $folder = '')
{
  if(is_array($css)) {
    foreach($css as $file => $folder)
      print htmlCSS($file, $folder);
    return '';
  }

  if(empty($folder))
    return '';
  return '<link rel="stylesheet" type="text/css" href="'.exido_fix_path($folder).$css.'.css" />'.EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Returns a javascript code between tags <script></script>.
 * @param string $code
 * @return string
 */
function htmlScript($code)
{
  if( ! empty($code))
    return '<script type="text/javascript"><!--'.EXIDO_EOL.$code.EXIDO_EOL.'--></script>'.EXIDO_EOL;
  else
    return '';
}

// -----------------------------------------------------------------------------

/**
 * Returns a css stylesheet between tags <style></style>.
 * @param string $code
 * @return string
 */
function htmlStyle($code)
{
  if( ! empty($code))
    return '<style>'.$code.'</style>'.EXIDO_EOL;
  else
    return '';
}

// -----------------------------------------------------------------------------

/**
 * Generates an HTML heading tag. First param is the data.
 * Second param is the size of the heading tag.
 * @param string $data
 * @param string $h
 * @return string
 */
function htmlHeading($data = '', $h = '3')
{
  return "<h".$h.">".$data."</h".$h.">".EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Generates HTML BR tags based on number supplied.
 * @param int $num
 * @return string
 */
function htmlBR($num = 1)
{
  return str_repeat("<br />", $num);
}

// -----------------------------------------------------------------------------

/**
 * Generates HTML <hr> tag.
 * @param string $class
 * @return string
 */
function htmlHR($class = 'hr')
{
  return '<hr class="'.$class.'"/>'.EXIDO_EOL;
}

// -----------------------------------------------------------------------------

/**
 * Generates non-breaking space entities based on number supplied.
 * @param int $num
 * @return string
 */
function htmlNbs($num = 1)
{
  return str_repeat("&nbsp;", $num);
}

// ---------------------------------------------------------------------------------

/**
 * Returns a div/span tag with a message.
 * @param string $msg
 * @param string $class
 * @param bool $use_span set TRUE if you need to use span instead div
 * @return string
 */
function htmlMsgBox($msg, $class = '-i-box -i-simple-box', $use_span = false)
{
  $tag = ($use_span) ? 'span' : 'div';
  return '<'.$tag.' class="'.$class.'">'.$msg.'</'.$tag.">".EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns an empty div tag.
 * @param string $id
 * @param string $class
 * @return string
 */
function htmlDiv($id = '', $class = '-i-simple-box')
{
  return '<div'.((empty($id))?'':' id="'.$id.'" ').((empty($class))?'':' class="'.$class.'" ')."></div>".EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a div open tag.
 * @param string $id
 * @param string $class
 * @return string
 */
function htmlOpenDiv($id = '', $class = '-i-simple-box')
{
  return '<div'.((empty($id))?'':' id="'.$id.'" ').((empty($class))?'':' class="'.$class.'"').">".EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a div close tag.
 * @param string $extra
 * @return string
 */
function htmlCloseDiv($extra = '')
{
  return "</div>".$extra.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a base tag.
 * @return string
 */
function htmlBase()
{
  return '<base href="'.HOME.'" />'.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a DOCTYPE tag.
 * @return string
 */
function htmlDoctype()
{
  return '<!DOCTYPE html>'.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a HTML open tag.
 * @return string
 */
function htmlOpen()
{
  return '<html>'.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a HTML close tag.
 * @param string $extra
 * @return string
 */
function htmlClose($extra = '')
{
  return "</html>".$extra.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a HEAD open tag.
 * @return string
 */
function htmlHeadOpen()
{
  return '<head>'.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a HEAD close tag.
 * @param string $extra
 * @return string
 */
function htmlHeadClose($extra = '')
{
  return "</head>".$extra.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a BODY open tag.
 * @return string
 */
function htmlBodyOpen()
{
  return '<body>'.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a BODY close tag.
 * @param string $extra
 * @return string
 */
function htmlBodyClose($extra = '')
{
  return "</body>".$extra.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Returns a status labels.
 * @param string $value
 * @param string $label_on
 * @param string $label_off
 * @return string
 */
function htmlStatus($value, $label_on = '', $label_off = '')
{
  if(empty($label_on))
    $label_on   = htmlMsgBox(__('Active'), '-i-label-active', true);
  if(empty($label_off))
    $label_off  = htmlMsgBox(__('Inactive'), '-i-label-inactive', true);

  return (empty($value)) ? $label_off : $label_on;
}

// ---------------------------------------------------------------------------------
/**
 * Close all opened tags
 * @param string $string
 * @return string
 */
function htmlCloseTags($string)
{
  $position = 0;
  $open_tags = array();
  // Ignore tags
  $ignored_tags = array('br', 'hr', 'img');

  while(($position = strpos($string, '<', $position)) !== false) {
    // Get all tags
    if(preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($string, $position), $match)) {
      $tag = strtolower($match[2]);
      // Ignore single tags
      if(in_array($tag, $ignored_tags) == false) {
        // Tag opened
        if(isset($match[1]) and $match[1] == '') {
          if (isset($open_tags[$tag]))
            $open_tags[$tag]++;
          else
            $open_tags[$tag] = 1;
        }
        // Tag closed
        if(isset($match[1]) and $match[1] == '/') {
          if (isset($open_tags[$tag]))
            $open_tags[$tag]--;
        }
      }
      $position += strlen($match[0]);
    } else
      $position++;
  }
  // Close tags
  foreach($open_tags as $tag => $count_not_closed)
    $string.= str_repeat("</{$tag}>", $count_not_closed);

  return $string;
}


?>