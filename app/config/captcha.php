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
   * ATTENTION: DO NOT change alphabet without changing font files!
   */
  'alphabet'        => '0123456789abcdefghijklmnopqrstuvwxyz',
  /**
   * Alphabet without similar symbols (o=0, 1=l, i=j, t=f)
   */
  'allowed_symbols' => '23456789abcdegikpqsvxyz',
  /**
   * Relative path to folder with fonts
   */
  'fontsdir'        => 'view/fonts',
  /**
   * Captcha string length, random 5 or 6 or 7
   * Can be changed to preferred length
   */
  'length'          => mt_rand(5,7),
  'width'           => 160,
  'height'          => 80,
  'fluctuation_amplitude' => 8,
  'white_noise_density'   => '1/6',
  'black_noise_density'   => '1/30',
  'no_spaces'        => true,
  'foreground_color' => array(mt_rand(0,80), mt_rand(0,80), mt_rand(0,80)),
  'background_color' => array(mt_rand(220,255), mt_rand(220,255), mt_rand(220,255)),
  'jpeg_quality'     => 90
);
?>