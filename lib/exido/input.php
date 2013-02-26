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
 * Input class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    29/06/2010
 * @version    1.0
 */
final class Input
{
  /**
   * User IP address
   * @var bool
   */
  public $ip_address = false;

  /**
   * Singleton instance
   * @var
   */
  private static $_instance;

  // ---------------------------------------------------------------------------

  /**
   * Gets the singleton instance
   * @return Input
   */
  public static function & instance()
  {
    if(self::$_instance === null)
      self::$_instance = new self;
    // Load a required helper
    Helper::load('array');
    if(get_magic_quotes_gpc()) {
      $_GET    = arrayStripSlashes($_GET);
      $_POST   = arrayStripSlashes($_POST);
      $_COOKIE = arrayStripSlashes($_COOKIE);
      $_FILES  = arrayStripSlashes($_FILES);
    }
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the value of given key.
   * @param string $key
   * @param bool $xss_clean_enabled
   * @return array|string
   */
  public function post($key = '', $xss_clean_enabled = true)
  {
    if( ! isset($_POST) or empty($_POST))
      return false;
    if(empty($key))
      return $_POST;
    if( ! isset($_POST[$key]))
      return false;
    // Sanitize variable
    if($xss_clean_enabled)
      return Registry::factory('security')->cleanXSS($_POST[$key]);
    return $_POST[$key];
  }

  // -----------------------------------------------------------------------------

  /**
   * Checks if the key exists in the $_POST.
   * @param string $key
   * @return bool
   */
  public function checkPost($key = '')
  {
    return (( ! isset($_POST) or empty($_POST)) and ! isset($_POST[$key])) ? false : true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the value of given key.
   * @param string $key
   * @return array|string
   */
  public function get($key = '')
  {
    if( ! isset($_GET) or empty($_GET))
      return false;
    if(empty($key))
      return $_GET;
    if( ! isset($_GET[$key]))
      return false;
    return $_GET[$key];
  }

  // -----------------------------------------------------------------------------

  /**
   * Checks if the key exists in the $_GET.
   * @param string $key
   * @return bool
   */
  public function checkGet($key = '')
  {
    return (( ! isset($_GET) or empty($_GET)) and ! isset($_GET[$key])) ? false : true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the value of given key.
   * @param string $key
   * @param bool $xss_clean_enabled
   * @return array|string
   */
  public function cookie($key = '', $xss_clean_enabled = true)
  {
    if( ! isset($_COOKIE) or empty($_COOKIE))
      return false;
    if(empty($key))
      return $_COOKIE;
    if( ! isset($_COOKIE[$key]))
      return false;
    // Sanitize variable
    if($xss_clean_enabled)
      return Registry::factory('security')->cleanXSS($_COOKIE[$key]);
    return $_COOKIE[$key];
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the value of given key.
   * @param string $key
   * @return array|string
   */
  public function server($key = '')
  {
    if( ! isset($_SERVER) or empty($_SERVER))
      return false;
    if(empty($key))
      return $_SERVER;
    if( ! isset($_SERVER[$key]))
      return false;
    return $_SERVER[$key];
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the host name.
   * @return string
   */
  public function host()
  {
    return $this->server('HTTP_HOST');
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the server name.
   * @return string
   */
  public function name()
  {
    return $this->server('SERVER_NAME');
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the value of given key.
   * @param string $key
   * @return array|string
   */
  public function files($key = '')
  {
    if( ! isset($_FILES) or empty($_FILES))
      return false;
    if(empty($key))
      return $_FILES;
    if( ! isset($_FILES[$key]))
      return false;
    return $_FILES[$key];
  }

  // ---------------------------------------------------------------------------

  /**
   * Fetches a user IP.
   * @param bool $return_array
   * @return string
   */
  public function ip($return_array = false)
  {
    if($this->ip_address !== false)
      return $this->ip_address;
    $proxy = Exido::config('proxy');
    $proxy = implode(',', $proxy->asArray());

    if( ! empty($proxy) && $this->server('HTTP_X_FORWARDED_FOR') && $this->server('REMOTE_ADDR')) {
      $proxies = preg_split('/[\s,]/', $proxy, -1, PREG_SPLIT_NO_EMPTY);
      $proxies = is_array($proxies) ? $proxies : array($proxies);
      $this->ip_address = in_array($this->server('REMOTE_ADDR'), $proxies) ? $this->server('HTTP_X_FORWARDED_FOR') : $this->server('REMOTE_ADDR');
    } elseif ($this->server('REMOTE_ADDR') and $this->server('HTTP_CLIENT_IP')) {
      $this->ip_address = $this->server('HTTP_CLIENT_IP');
    } elseif ($this->server('REMOTE_ADDR')) {
      $this->ip_address = $this->server('REMOTE_ADDR');
    } elseif ($this->server('HTTP_CLIENT_IP')) {
      $this->ip_address = $this->server('HTTP_CLIENT_IP');
    } elseif ($this->server('HTTP_X_FORWARDED_FOR')) {
      $this->ip_address = $this->server('HTTP_X_FORWARDED_FOR');
    }

    if ($this->ip_address === false)
      return $this->ip_address = '0.0.0.0';
    if(strstr($this->ip_address, ',')) {
      $x = explode(',', $this->ip_address);
      $this->ip_address = trim(end($x));
    }
    if( ! $this->validateIP($this->ip_address))
      $this->ip_address = '0.0.0.0';
    if($return_array) {
      return explode('.', $this->ip_address);
    }
    return $this->ip_address;
  }

  // ---------------------------------------------------------------------------

  /**
   * Check if the request is an XML request
   * @return bool
   */
  public function isXmlRequest()
  {
    return strtolower(self::server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
  }

  // ---------------------------------------------------------------------------

  /**
   * Validates an IP Address. Return TRUE if IP is correct, otherwise FALSE.
   * @param string $ip
   * @return bool
   */
  public function validateIP($ip)
  {
    $segments = explode('.', $ip);
    // Always 4 segments needed
    if(count($segments) != 4)
      return false;
    // IP can not start with 0
    if($segments[0][0] == '0')
      return false;
    // Check each segment
    foreach($segments as $segment) {
      // IP segments must be digits and can not be
      // longer than 3 digits or greater then 255
      if($segment == ''
        or preg_match("/[^0-9]/", $segment)
        or $segment > 255
        or strlen($segment) > 3)
        return false;
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Fetches a user agent
   * @return string
   */
  public function useragent()
  {
    return self::server('HTTP_USER_AGENT');
  }
}

?>