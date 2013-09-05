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

require_once 'image/base.php';
require_once 'image/interface/base.php';
require_once 'image/interface/gd.php';

// -----------------------------------------------------------------------------

/**
 * Image manipulation class - GD version.
 * @package    core
 * @subpackage image
 * @copyright  Sharapov A.
 * @created    29/01/2010
 * @version    1.0
 */
final class Exido_Image_Gd extends Exido_Image_Abstract implements Exido_Image_Interface_Gd, Exido_Image_Interface_Abstract
{
  // Watermark properties
  public $wm_text             = ''; // Watermark text if graphic is not used
  public $wm_type             = ''; // Type of watermarking. Options: text/overlay
  public $wm_x_transp         = 4;
  public $wm_y_transp         = 4;
  public $wm_overlay_path     = ''; // Watermark image path
  public $wm_font_path        = ''; // TT font
  public $wm_font_size        = 17; // Font size (different versions of GD will either use points or pixels)
  public $wm_vrt_alignment    = 'M'; // Vertical alignment:   T - top, M - middle, B - bottom
  public $wm_hor_alignment    = 'C'; // Horizontal alignment: L - left, R - right, C - center
  public $wm_padding          = 0; // Padding around text
  public $wm_hor_offset       = 0; // Lets you push text to the right
  public $wm_vrt_offset       = 0; // Lets you push  text down
  public $wm_font_color       = '#ffffff'; // Text color
  public $wm_shadow_color     = ''; // Dropshadow color
  public $wm_shadow_distance  = 2; // Dropshadow distance
  public $wm_opacity          = 50; // Image opacity: 1 - 100 Only works with image

  protected $_source_folder      = '';
  protected $_dest_folder        = '';
  protected $_dest_image         = '';
  protected $_mime_type          = '';
  protected $_orig_width         = '';
  protected $_orig_height        = '';
  protected $_image_type         = '';
  protected $_new_image_ext      = '';
  protected $_size_str           = '';
  protected $_full_src_path      = '';
  protected $_full_dst_path      = '';
  protected $_actions            = array('crop', 'resize', 'rotate', 'mirror', 'watermark', 'base64');
  protected $_degs               = array(); // Allowed rotation values
  protected $_display            = true;
  protected $_base64             = false;
  protected $_wm_use_drop_shadow = false;
  protected $_wm_use_truetype    = false;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @param array $params
   * @throws Exception_Exido
   */
  public function __construct($params = array())
  {
    if( ! $this->_isGdLoaded()) {
      throw new Exception_Exido(__("The GD library doesn't installed."));
    }
    parent::__construct($params);
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the GD version.
   * @return bool|mixed
   */
  public function getGdVersion()
  {
    if(function_exists('gd_info')) {
      $vers = @gd_info();
      return preg_replace("/\D/", "", $vers['GD Version']);
    }
    return false;
  }

  // ---------------------------------------------------------------------------

  /**
   * Throws a processed image to browser.
   * @param string $action
   * @param array $params
   * @return object
   */
  public function action($action, array $params = array())
  {
    $global = get_object_vars($this);
    $global['action'] = $action;
    return new self(array_merge($global, $params));
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets information about the image file.
   * @param string $path
   * @return bool
   */
  public function getImageProperties($path)
  {
    if( ! file_exists($path)) {
      return false;
    }
    if( ! $vals = @getimagesize($path)) {
      return false;
    }
    if( ! isset($vals['mime'])) {
      return false;
    }
    if( ! in_array($vals['mime'], $this->mimes)) {
      return false;
    }
    $v['width']      = $vals['0'];
    $v['height']     = $vals['1'];
    $v['image_type'] = $vals['2'];
    $v['size_str']   = $vals['3'];
    $v['mime_type']  = $vals['mime'];
    return $v;
  }

  // ---------------------------------------------------------------------------

  /**
   * Process an image.
   * @return bool
   * @throws Exception_Exido
   */
  protected function _processImage()
  {
    if($this->action == '' or ! in_array($this->action, $this->_actions)) {
      throw new Exception_Exido(__("The method %s you called isn't found."), array(
        $this->action
      ));
    }

    // Is there a source image? If not, there's no reason to continue
    if($this->source_image_path == '') {
      throw new Exception_Exido(__("You must specify a source image in your preferences."));
    }

    // The source image may or may not contain a path.
    // Either way, we'll try use realpath to generate the
    // full server path in order to more reliably read it.
    if(function_exists('realpath') and @realpath($this->source_image_path) !== false) {
      $full_source_path = str_replace("\\", "/", realpath($this->source_image_path));
    } else {
      $full_source_path = $this->source_image_path;
    }

    $x = explode('/', $full_source_path);
    $this->source_image_path = end($x);
    $this->_source_folder = str_replace($this->source_image_path, '', $full_source_path);

    // Is getimagesize() available?
    // We use it to determine the image properties (width/height).
    if( ! function_exists('getimagesize')) {
      throw new Exception_Exido(__("Your server must support the GD image library in order to determine the image properties."));
    }

    // Get image properties
    if( ! $image_props = $this->getImageProperties($this->_source_folder.$this->source_image_path)) {
      throw new Exception_Exido(__("Couldn't get the image properties. Perhaps it's not an image."));
    }

    if($this->action == 'resize') {
      pre($image_props);
    }
    // Set image properties
    $this->_orig_width  = $image_props['width'];
    $this->_orig_height = $image_props['height'];
    $this->_image_type  = $image_props['image_type'];
    $this->_size_str    = $image_props['size_str'];
    $this->_mime_type   = $image_props['mime_type'];

    // If the user has set a "new_image_path" name it means
    // we are making a copy of the source image. If not
    // it means we are altering the original. We'll
    // set the destination filename and path accordingly.
    if($this->new_image_path == '') {
      $this->_dest_image  = $this->source_image_path;
      $this->_dest_folder = $this->_source_folder;
    } else {
      if(strpos($this->new_image_path, '/') === false) {
        $this->_dest_folder = $this->_source_folder;
        $this->_dest_image  = $this->new_image_path;
      } else {
        if(function_exists('realpath') and @realpath($this->new_image_path) !== false) {
          $full_dest_path = str_replace("\\", "/", realpath($this->new_image_path));
        } else {
          $full_dest_path = $this->new_image_path;
        }

        // Is there a file name?
        if( ! preg_match("#\.(jpg|jpeg|gif|png)$#i", $full_dest_path))
        {
          $this->_dest_folder = $full_dest_path.'/';
          $this->_dest_image  = $this->source_image_path;
        } else {
          $x = explode('/', $full_dest_path);
          $this->_dest_image  = end($x);
          $this->_dest_folder = str_replace($this->_dest_image, '', $full_dest_path);
        }
      }
    }

    // When creating thumbs or copies, the target width/height
    // might not be in correct proportion with the source
    // image's width/height.  We'll recalculate it here.
    if($this->maintain_ratio === true && ($this->width != '' and $this->height != '')) {
      $this->_reproportionImage();
    }

    // We'll create two master strings containing the
    // full server path to the source image and the
    // full server path to the destination image.
    // We'll also split the destination image name
    // so we can insert the thumbnail marker if needed.
    if($this->create_thumb === false or $this->thumb_prfx == '') {
      $this->thumb_prfx = '';
    }
    $xp  = $this->explodeName($this->_dest_image);
    $filename = $xp['name'];
    //$file_ext = $xp['ext'];
    // Get file extension
    $this->_new_image_ext = '.'.array_search($this->_mime_type, $this->mimes);
    $this->_full_src_path = $this->_source_folder.$this->source_image_path;
    $this->_full_dst_path = $this->_dest_folder.$this->thumb_prfx.$filename.$this->_new_image_ext;
    // If the destination width/height was
    // not submitted we will use the values
    // from the actual file
    if($this->width == '') {
      $this->width = $this->_orig_width;
    }
    if($this->height == '') {
      $this->height = $this->_orig_height;
    }
    // Set the quality
    $this->quality = trim($this->quality);
    if($this->quality == '' or $this->quality < 1 or ! is_numeric($this->quality)) {
      $this->quality = 90;
    }
    // Set the x/y coordinates
    $this->x_axis = ($this->x_axis == '' or ! is_numeric($this->x_axis)) ? 0 : $this->x_axis;
    $this->y_axis = ($this->y_axis == '' or ! is_numeric($this->y_axis)) ? 0 : $this->y_axis;
    // Process method name
    $use_method = '_'.$this->action.'Image';
    // Call the specified method
    if( ! $result = $this->$use_method()) {
      return false;
    }
    // Return Base64 string
    if($this->action == 'base64') {
      return $result;
    } else {
      // Display the Image
      if($this->_display == true) {
        $this->_displayImage($result);
      } else {
        // Or save it
        if( ! $this->_saveImage($result)) {
          return false;
        }
      }
      // Kill the file handles
      imagedestroy($result);
      // Set the file to 777
      @chmod($this->_full_dst_path, 0777);
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Resizes an image.
   * @return bool
   */
  private function _resizeImage()
  {
    // If resizing the x/y axis must be zero
    $this->x_axis = 0;
    $this->y_axis = 0;
    //  Create the image handle
    if( ! ($src_img = $this->_createImage())) {
      return false;
    }
    // Create the image
    if(function_exists('imagecreatetruecolor')) {
      $create = 'imagecreatetruecolor';
      $copy   = 'imagecopyresampled';
    } else {
      $create = 'imagecreate';
      $copy   = 'imagecopyresized';
    }
    if($this->_orig_width <= $this->width and $this->_orig_height <= $this->height and $this->increase_small == false) {
      $dst_img = $create($this->_orig_width, $this->_orig_height);
      $copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->_orig_width, $this->_orig_height, $this->_orig_width, $this->_orig_height);
    } else {
      $dst_img = $create($this->width, $this->height);
      $copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->width, $this->height, $this->_orig_width, $this->_orig_height);
    }
    imagedestroy($src_img);
    return $dst_img;
  }

  // ---------------------------------------------------------------------------

  /**
   * Crops an image.
   * @return bool
   */
  private function _cropImage()
  {
    //  Create the image handle
    if( ! ($src_img = $this->_createImage())) {
      return false;
    }
    // Reassign the source width/height if cropping
    $this->_orig_width  = $this->width;
    $this->_orig_height = $this->height;
    // Create the image
    if(function_exists('imagecreatetruecolor')) {
      $create = 'imagecreatetruecolor';
      $copy   = 'imagecopyresampled';
    } else {
      $create = 'imagecreate';
      $copy   = 'imagecopyresized';
    }
    $dst_img = $create($this->width, $this->height);
    $copy($dst_img, $src_img, 0, 0, $this->x_axis, $this->y_axis, $this->width, $this->height, $this->_orig_width, $this->_orig_height);
    imagedestroy($src_img);
    return $dst_img;
  }

  // ---------------------------------------------------------------------------

  /**
   * Creates a mirror image.
   * @return bool|mixed
   */
  private function _mirrorImage()
  {
    //  Create the image handle
    if( ! ($src_img = $this->_createImage())) {
      return false;
    }
    $width  = $this->_orig_width;
    $height = $this->_orig_height;
    if($this->rotation_angle != 'hor' and $this->rotation_angle != 'vrt') {
      return false;
    }
    if($this->rotation_angle == 'hor') {
      for ($i = 0; $i < $height; $i++) {
        $left  = 0;
        $right = $width-1;
        while($left < $right) {
          $cl = imagecolorat($src_img, $left, $i);
          $cr = imagecolorat($src_img, $right, $i);
          imagesetpixel($src_img, $left, $i, $cr);
          imagesetpixel($src_img, $right, $i, $cl);
          $left++;
          $right--;
        }
      }
    } else {
      for ($i = 0; $i < $width; $i++) {
        $top = 0;
        $bot = $height-1;
        while($top < $bot) {
          $ct = imagecolorat($src_img, $i, $top);
          $cb = imagecolorat($src_img, $i, $bot);
          imagesetpixel($src_img, $i, $top, $cb);
          imagesetpixel($src_img, $i, $bot, $ct);
          $top++;
          $bot--;
        }
      }
    }
    return $src_img;
  }

  // ---------------------------------------------------------------------------

  /**
   * Rotates image.
   * @return bool|resource
   * @throws Exception_Exido
   */
  private function _rotateImage()
  {
    if($this->rotation_angle == '' or ! in_array($this->rotation_angle, $this->_degs)) {
      throw new Exception_Exido(__("An angle of rotation is required to rotate the image."));
    }
    if( ! function_exists('imagerotate')) {
      throw new Exception_Exido(__("Image rotation does not appear to be supported by your server."));
    }
    //  Create the image handle
    if( ! ($src_img = $this->_createImage())) {
      return false;
    }
    // Set the background color
    // This won't work with transparent PNG files so we are
    // going to have to figure out how to determine the color
    // of the alpha channel in a future release.
    $white = imagecolorallocate($src_img, 255, 255, 255);
    // Rotate it!
    return imagerotate($src_img, $this->rotation_angle, $white);
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns base64 string.
   * @return bool|resource
   */
  private function _base64Image()
  {
    //  Create the image handle
    if( ! ($src_img = $this->_createImage())) {
      return false;
    }
    // Create temp file
    $this->_full_dst_path = tempnam(sys_get_temp_dir(), uniqid('b64_', true));
    // Save image
    $this->_saveImage($src_img);
    // Generate base64 string
    return base64_encode(file_get_contents($this->_full_dst_path));
  }

  // ---------------------------------------------------------------------------

  /**
   * Creates an image using GD library. This simply create an image resource handle
   * based on the type of image being processed.
   * @param string $path
   * @param string $image_type
   * @return resource
   * @throws Exception_Exido
   */
  private function _createImage($path = '', $image_type = '')
  {
    if($path == '') {
      $path = $this->_full_src_path;
    }

    if($image_type == '') {
      $image_type = $this->_image_type;
    }

    switch($image_type) {
      case 1 :
        if( ! function_exists('imagecreatefromgif')) {
          throw new Exception_Exido(__("GIF images are often not supported."));
        }
        return imagecreatefromgif($path);
        break;

      case 2 :
        if( ! function_exists('imagecreatefromjpeg')) {
          throw new Exception_Exido(__("JPG images are often not supported."));
        }
        return imagecreatefromjpeg($path);
        break;

      case 3 :
        if( ! function_exists('imagecreatefrompng')) {
          throw new Exception_Exido(__("PNG images are often not supported."));
        }
        return imagecreatefrompng($path);
        break;

      default :
        throw new Exception_Exido(__("Your server does not support the GD function required to process this type of image."));
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Writes an image to disk. Takes an image resource as input and writes the file
   * to the specified destination.
   * @param resource $resource
   * @return bool
   * @throws Exception_Exido
   */
  private function _saveImage($resource)
  {
    switch($this->_image_type) {
      case 1 :
        if( ! function_exists('imagegif')) {
          throw new Exception_Exido(__("GIF images are often not supported."));
        }
        if( ! @imagegif($resource, $this->_full_dst_path)) {
          throw new Exception_Exido(__("Couldn't save a GIF image."));
        }
        break;

      case 2  :
        if( ! function_exists('imagejpeg')) {
          throw new Exception_Exido(__("JPG images are often not supported."));
        }
        if( ! @imagejpeg($resource, $this->_full_dst_path, $this->quality)) {
          throw new Exception_Exido(__("Couldn't save a JPG image."));
        }
        break;

      case 3  :
        if( ! function_exists('imagepng')) {
          throw new Exception_Exido(__("PNG images are often not supported."));
        }
        if( ! @imagepng($resource, $this->_full_dst_path)) {
          throw new Exception_Exido(__("Couldn't save a PNG image."));
        }
        break;

      default :
        throw new Exception_Exido(__("Your server does not support the GD function required to process this type of image."));
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Dynamically output an image.
   * @param resource $resource
   * @return  void
   */
  private function _displayImage($resource)
  {
    header('Content-Disposition: filename='.$this->source_image_path);
    header('Content-Type: '.$this->_mime_type);
    header('Content-Transfer-Encoding: binary');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');

    switch($this->_image_type) {
      case 1   : @imagegif($resource);
        break;
      case 2   : @imagejpeg($resource, '', $this->quality);
        break;
      case 3   : @imagepng($resource);
        break;
      default  :
        print __('Unable to display image.');
        break;
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Checks if GD library is installed.
   * @return bool
   */
  private function _isGdLoaded()
  {
    if( ! @extension_loaded('gd')) {
      if( ! @dl('gd.so')) {
        return false;
      }
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Watermark. This is a wrapper function that chooses the type
   * of watermarking based on the specified preference.
   * @return mixed
   */
  private function _watermarkImage()
  {
    if($this->wm_type == 'overlay') {
      return $this->_overlayWatermark();
    } else {
      return $this->_textWatermark();
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Watermark - Graphic Version
   * @return bool|resource
   * @throws Exception_Exido
   */
  private function _overlayWatermark()
  {
    if( ! function_exists('imagecolortransparent')) {
      throw new Exception_Exido(__("The GD image library is required for this feature."));
    }
    // Fetch source image properties
    $image_props = $this->getImageProperties($this->_full_src_path);
    // Fetch watermark image properties
    $props       = $this->getImageProperties($this->wm_overlay_path);
    $wm_img_type  = $props['image_type'];
    $wm_width     = $props['width'];
    $wm_height    = $props['height'];
    // Create two image resources
    $wm_img  = $this->_createImage($this->wm_overlay_path, $wm_img_type);
    $src_img = $this->_createImage($this->_full_src_path);
    // Reverse the offset if necessary
    // When the image is positioned at the bottom
    // we don't want the vertical offset to push it
    // further down.  We want the reverse, so we'll
    // invert the offset.  Same with the horizontal
    // offset when the image is at the right
    $this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
    $this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));
    if($this->wm_vrt_alignment == 'B') {
      $this->wm_vrt_offset = $this->wm_vrt_offset * -1;
    }
    if($this->wm_hor_alignment == 'R') {
      $this->wm_hor_offset = $this->wm_hor_offset * -1;
    }
    //  Set the base x and y axis values
    $x_axis = $this->wm_hor_offset + $this->wm_padding;
    $y_axis = $this->wm_vrt_offset + $this->wm_padding;
    // Set the vertical position
    switch($this->wm_vrt_alignment) {
      case 'T':
        break;
      case 'M':  $y_axis += ($image_props['height'] / 2) - ($wm_height / 2);
        break;
      case 'B':  $y_axis += $image_props['height'] - $wm_height;
        break;
    }
    // Set the horizontal position
    switch($this->wm_hor_alignment) {
      case 'L':
        break;
      case 'C':  $x_axis += ($image_props['width'] / 2) - ($wm_width / 2);
        break;
      case 'R':  $x_axis += $image_props['width'] - $wm_width;
        break;
    }
    // Build the finalized image
    if($wm_img_type == 3 and function_exists('imagealphablending')) {
      @imagealphablending($src_img, true);
    }
    // Set RGB values for text and shadow
    $rgba   = imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp);
    $alpha  = ($rgba & 0x7F000000) >> 24;
    // Make a best guess as to whether we're dealing with an image with alpha transparency or no/binary transparency
    if($alpha > 0) {
      // copy the image directly, the image's alpha transparency being the sole determinant of blending
      imagecopy($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height);
    } else {
      // set our RGB value from above to be transparent and merge the images with the specified opacity
      imagecolortransparent($wm_img, imagecolorat($wm_img, $this->wm_x_transp, $this->wm_y_transp));
      imagecopymerge($src_img, $wm_img, $x_axis, $y_axis, 0, 0, $wm_width, $wm_height, $this->wm_opacity);
    }
    imagedestroy($wm_img);
    return $src_img;
  }

  // ---------------------------------------------------------------------------

  /**
   * Watermark - Text Version
   * @return bool|resource
   * @throws Exception_Exido
   */
  private function _textWatermark()
  {
    if($this->_wm_use_truetype == true and ! file_exists($this->wm_font_path)) {
      throw new Exception_Exido(__("Unable to find the font."));
    }
    // Fetch source image properties
    $image_props = $this->getImageProperties($this->_full_src_path);
    if( ! ($src_img = $this->_createImage())) {
      return false;
    }
    // Set RGB values for text and shadow
    $this->wm_font_color    = str_replace('#', '', $this->wm_font_color);
    $this->wm_shadow_color  = str_replace('#', '', $this->wm_shadow_color);
    $R1 = hexdec(substr($this->wm_font_color, 0, 2));
    $G1 = hexdec(substr($this->wm_font_color, 2, 2));
    $B1 = hexdec(substr($this->wm_font_color, 4, 2));
    $R2 = hexdec(substr($this->wm_shadow_color, 0, 2));
    $G2 = hexdec(substr($this->wm_shadow_color, 2, 2));
    $B2 = hexdec(substr($this->wm_shadow_color, 4, 2));
    $txt_color  = imagecolorclosest($src_img, $R1, $G1, $B1);
    $drp_color  = imagecolorclosest($src_img, $R2, $G2, $B2);

    // Reverse the vertical offset
    // When the image is positioned at the bottom
    // we don't want the vertical offset to push it
    // further down.  We want the reverse, so we'll
    // invert the offset.  Note: The horizontal
    // offset flips itself automatically
    if($this->wm_vrt_alignment == 'B') {
      $this->wm_vrt_offset = $this->wm_vrt_offset * -1;
    }
    if($this->wm_hor_alignment == 'R') {
      $this->wm_hor_offset = $this->wm_hor_offset * -1;
    }

    // Set font width and height
    // These are calculated differently depending on
    // whether we are using the true type font or not
    if($this->_wm_use_truetype == true) {
      if($this->wm_font_size == '') {
        $this->wm_font_size = '17';
      }
      $fontwidth  = $this->wm_font_size-($this->wm_font_size/4);
      $fontheight = $this->wm_font_size;
      $this->wm_vrt_offset += $this->wm_font_size;
    } else {
      $fontwidth  = imagefontwidth($this->wm_font_size);
      $fontheight = imagefontheight($this->wm_font_size);
    }

    // Set base X and Y axis values
    $x_axis = $this->wm_hor_offset + $this->wm_padding;
    $y_axis = $this->wm_vrt_offset + $this->wm_padding;

    // Set verticle alignment
    if($this->_wm_use_drop_shadow == false) {
      $this->wm_shadow_distance = 0;
    }

    $this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
    $this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

    switch($this->wm_vrt_alignment) {
      case   "T" :
        break;
      case "M":  $y_axis += ($image_props['height'] / 2)+($fontheight/2);
        break;
      case "B":  $y_axis += ($image_props['height']- $fontheight - $this->wm_shadow_distance - ($fontheight/2));
        break;
    }

    $x_shad = $x_axis + $this->wm_shadow_distance;
    $y_shad = $y_axis + $this->wm_shadow_distance;

    // Set horizontal alignment
    switch($this->wm_hor_alignment) {
      case "L":
        break;
      case "R":
        if($this->_wm_use_drop_shadow)
          $x_shad += ($image_props['width'] - $fontwidth*strlen($this->wm_text));
        $x_axis += ($image_props['width'] - $fontwidth*strlen($this->wm_text));
        break;
      case "C":
        if($this->_wm_use_drop_shadow)
          $x_shad += floor(($image_props['width'] - $fontwidth*strlen($this->wm_text))/2);
        $x_axis += floor(($image_props['width']  -$fontwidth*strlen($this->wm_text))/2);
        break;
    }
    // Add the text to the source image
    if($this->_wm_use_truetype) {
      if($this->_wm_use_drop_shadow) {
        imagettftext($src_img, $this->wm_font_size, 0, $x_shad, $y_shad, $drp_color, $this->wm_font_path, $this->wm_text);
        imagettftext($src_img, $this->wm_font_size, 0, $x_axis, $y_axis, $txt_color, $this->wm_font_path, $this->wm_text);
      }
    } else {
      if($this->_wm_use_drop_shadow) {
        imagestring($src_img, $this->wm_font_size, $x_shad, $y_shad, $this->wm_text, $drp_color);
        imagestring($src_img, $this->wm_font_size, $x_axis, $y_axis, $this->wm_text, $txt_color);
      }
    }
    return $src_img;
  }
}

?>