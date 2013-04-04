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
 * Generates an HTML table heading row.
 * @param array $tds
 * @param string $class
 * @return string
 */
function tableHead(array $tds, $class = '')
{
  return tableTR($tds, 'th', $class);
}

// -----------------------------------------------------------------------------

/**
 * Generates an HTML table rows.
 * @param array $tds
 * @param bool $use_th
 * @param string $class
 * @return string
 */
function tableTR(array $tds, $use_th = false, $class = '')
{
  if(empty($tds)) {
    return '';
  }
  $tag = ($use_th) ? 'th' : 'td';
  $output = '<tr'.(( ! empty($class)) ? ' class="'.$class.'"' : '').'>';
  foreach($tds as $value) {
    $output.= '<'.$tag.'>'.$value.'</'.$tag.'>';
  }
  $output.= '</tr>';
  return $output;
}

// -----------------------------------------------------------------------------

/**
 * Generates an HTML table td tag.
 * @param string $value
 * @param bool $use_th
 * @return string
 */
function tableTD($value = '', $use_th = false)
{
  $tag = ($use_th) ? 'th' : 'td';
  return '<'.$tag.'>'.$value.'</'.$tag.'>';
}

// -----------------------------------------------------------------------------

/**
 * Generates an HTML table open tag.
 * @param string $class
 * @param string $border
 * @return string
 */
function tableOpen($class = '', $border = '')
{
  if( ! empty($class)) {
    $class = ' class="'.$class.'"';
  }
  return '<table'.$class.((is_numeric($border)?' border="'.$border.'"':'')).'>'.EXIDO_EOL;
}

// ---------------------------------------------------------------------------------

/**
 * Generate a HTML table close tag.
 * @param string $extra
 * @return string
 */
function tableClose($extra = '')
{
  return "</table>".$extra.EXIDO_EOL;
}

?>