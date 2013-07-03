<?php defined('SYSPATH') or die('No direct script access allowed.');

/*******************************************************************************
 * ExidoEngine Content Management System
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (3.0)
 * that is bundled with this package in the file license_en.txt
 * It is also available through the world-wide-web at this URL:
 * http://exidoengine.com/license/gpl-3.0.html
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
 * @license   http://exidoengine.com/license/gpl-3.0.html (GNU General Public License v3)
 * @author    ExidoTeam
 * @copyright Copyright (c) 2009 - 2012, ExidoEngine Solutions
 * @link      http://exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

/**
 * Image manipulation class.
 * @package    core
 * @subpackage image
 * @copyright  Sharapov A.
 * @created    29/01/2010
 * @version    1.0
 */
abstract class Image_Base
{
  public $source_image_path = '';
  public $new_image_path    = '';
  public $create_thumb    = true;
  public $thumb_prfx      = '_tmb_';

  /*
   * Whether to maintain aspect ratio when resizing or use hard values
   * @var
   */
  public $maintain_ratio  = true;

  /*
   * auto, height, or width. Determines what to use as the master dimension
   * @var
   */
  public $master_dim      = 'auto';
  public $rotation_angle  = '';
  public $x_axis          = '';
  public $y_axis          = '';
  public $width           = '';
  public $height          = '';
  public $quality         = '90';
  public $action          = '';
  public $increase_small  = false;
  public $actions         = array();

  /*
   * Allowed mimes
   */
  public $mimes = array(
    'jpg'  => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif'
  );

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param null $params
   */
  public function __construct($params = null)
  {
    if( ! is_array($params)) {
      $params = array();
    }
    $params['degs'] = range(1, 360);

    if( ! isset($params['mimes'])) {
      // Get mimes from config file
      if($mimes = Exido::config('mime.image')) {
        $params['mimes'] = $mimes;
      }
    }

    $this->setup($params);
  }

  // ---------------------------------------------------------------------------

  /**
   * Setup image preferences.
   * @param array $params
   * @return void
   */
  public function setup(array $params)
  {
    // Convert array elements into class properties
    if(count($params) > 0) {
      foreach($params as $key => $val) {
        $this->$key = $val;
      }
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Resets the values in case this class is used in a loop.
   * @return void
   */
  public function clear()
  {
    $props = array( '_source_folder',
      '_dest_folder',
      'source_image_path',
      '_full_src_path',
      '_full_dst_path',
      'new_image_path',
      '_image_type',
      '_size_str',
      'quality',
      '_orig_width',
      '_orig_height',
      'rotation_angle',
      'x_axis',
      'y_axis',
      'wm_overlay_path',
      'wm_use_truetype',
      'wm_font_size',
      'wm_text',
      'wm_vrt_alignment',
      'wm_hor_alignment',
      'wm_padding',
      'wm_hor_offset',
      'wm_vrt_offset',
      'wm_font_color',
      'wm_use_drop_shadow',
      'wm_shadow_color',
      'wm_shadow_distance',
      'wm_opacity'
    );
    foreach($props as $val) {
      $this->$val = '';
    }
    // Special consideration for master_dim
    $this->master_dim = 'auto';
  }

  // ---------------------------------------------------------------------------

  /**
   * Throws a processed image to browser.
   * @return void
   */
  public function get()
  {
    $this->_display = true;
    if($this->action == 'base64') {
      return $this->_processImage();
    }
    $this->_processImage();
  }

  // ---------------------------------------------------------------------------

  /**
   * Saves a processed image to disk.
   * @return mixed
   */
  public function save()
  {
    $this->_display = false;
    return $this->_processImage();
  }

  // ---------------------------------------------------------------------------

  /**
   * Explodes an image filename.
   * @param string $image
   * @return array
   */
  public function explodeName($image)
  {
    $ext = strrchr($image, '.');
    if($ext === false) {
      $name = $image;
    } else {
      $name = substr($image, 0, -strlen($ext));
      $ext  = ($ext == '.jpeg') ? '.jpg' : $ext;
    }
    return array
    (
      'ext'  => $ext,
      'name' => $name
    );
  }

  // ---------------------------------------------------------------------------

  /**
   * Re-proportion Image Width/Height
   *
   * When creating thumbs, the desired width/height
   * can end up warping the image due to an incorrect
   * ratio between the full-sized image and the thumb.
   *
   * This function lets us re-proportion the width/height
   * if users choose to maintain the aspect ratio when resizing.
   * @return  void
   */
  protected function _reproportionImage()
  {
    if( ! is_numeric($this->width) or ! is_numeric($this->height) or $this->width == 0 or $this->height == 0) {
      return;
    }
    if( ! is_numeric($this->_orig_width) or ! is_numeric($this->_orig_height) or $this->_orig_width == 0 or $this->_orig_height == 0) {
      return;
    }

    $new_width  = ceil($this->_orig_width * $this->height/$this->_orig_height);
    $new_height = ceil($this->width * $this->_orig_height/$this->_orig_width);
    $ratio = (($this->_orig_height/$this->_orig_width) - ($this->height/$this->width));

    if($this->master_dim != 'width' and $this->master_dim != 'height') {
      $this->master_dim = ($ratio < 0) ? 'width' : 'height';
    }
    if(($this->width != $new_width) and ($this->height != $new_height)) {
      if($this->master_dim == 'height') {
        $this->width  = $new_width;
      } else {
        $this->height = $new_height;
      }
    }
  }
}

?>