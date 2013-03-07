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
 * @license   http://www.exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://www.exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

return array(
  /**
   * MIME types of files allowed for uploading
   */
  'image' => array
  (
    'jpeg' => 'image/jpeg',
    'jpg'  => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif'
  ),
  'plaintext' => array
  (
    'txt' => 'text/plain',
    'doc' => 'application/msword'
  ),
  'msword' => array
  (
    'doc' => 'application/msword',
    'rtf' => 'application/msword'
  ),
  'msexcel' => array
  (
    'xls' => 'application/x-msexcel'
  ),
  'adobepdf' => array
  (
    'pdf' => 'application/pdf'
  ),
  'audio' => array
  (
    'mp3' => 'audio/mpeg'
  ),
  'flash' => array
  (
    'swf' => 'application/x-shockwave-flash'
  )
);

?>