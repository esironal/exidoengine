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
 * System routing class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
final class Router
{
  public static $current_uri  = '';
  public static $routed_uri   = '';
  public static $url_suffix   = '';
  public static $segments     = array();
  public static $rsegments    = array();
  public static $controller;
  public static $controller_path;
  public static $controller_view;
  public static $method     = 'index';
  public static $default    = 'front';
  public static $arguments  = array();
  public static $web_root   = '';

  protected static $_routes;
  protected static $_additional_routes = array();

  // ---------------------------------------------------------------------------

  /**
   * Setup routine.
   * Automatically called during the core initialization process.
   * @return bool
   * @throws Exception_Exido
   * @throws Exception_Exido_404
   */
  public static function initialize()
  {
    if(self::$_routes === null) {
      // Load routes
      self::$_routes = Exido::config('route');
    }

    // Debug log
    if(Exido::$log_debug) Exido::$log->add('EXIDO_DEBUG_LOG', __('Initialize routing'));

    $default_route = false;

    if(self::$current_uri == Exido::config('global.core.index_file')) {
      self::$current_uri = '';
    }

    if( ! isset(self::$_routes['default_method'])) {
      self::$_routes['default_method'] = self::$method;
    }

    // Remove web-root directory
    if(WEB_ROOT != '') {
      self::$web_root    = trim(WEB_ROOT, '/');
      // Remove the suffix from the URL if needed
      self::$current_uri = trim(preg_replace("|^".self::$web_root."|", "", self::$current_uri), '/');
    }

    if(self::$current_uri == '') {
      // If default controller is not set
      if( ! isset(self::$_routes['default_controller']) or self::$_routes['default_controller'] == '') {
        self::$_routes['default_controller'] = self::$default;
      }
      // Use the default route when no segments exist
      self::$current_uri = self::$_routes['default_controller'];
      // Default route is in use
      $default_route = true;
    }

    if($default_route == false) {
      // Remove the suffix from the URL if needed
      self::$current_uri = preg_replace("|".preg_quote(Exido::config('global.core.url_suffix'))."$|", "", self::$current_uri);
      // Explode the segments by slashes
      foreach(explode("/", preg_replace("|/*(.+?)/*$|", "\\1", self::$current_uri)) as $val) {
        $val = trim($val);
        // Check for allowed characters
        if(Exido::config('global.core.permitted_uri_chars') != '' and $val != '') {
          // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
          // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
          if( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote(Exido::config('global.core.permitted_uri_chars'), '-'))."]+$|i", $val)) {
            throw new Exception_Exido('The requested URL %s has a disallowed characters', array($val));
          }
        }
        if($val != '') self::$segments[] = self::$rsegments[] = $val;
      }
      // Custom routing
      if(count(self::$_routes) > 0)
       self::$rsegments = self::_getRouted(self::$segments);//array_merge(self::_getRouted(self::$segments), self::$segments);
    }
    if($default_route == true)
      self::$rsegments[] = self::$current_uri;
    // Prepare to find the controller
    $controller_path = '';
    $method_segment  = null;
    $controller_name = array();

    // Paths to search
    $paths = Exido::getIncludePaths();
    foreach(self::$rsegments as $key => $segment) {
      // Add the segment to the search path
      $controller_path.= $segment;
      $found = false;
      // Set segment into controller name
      $controller_name[] = ucfirst($segment);
      foreach($paths as $dir) {
        // Search within controllers only
        $dir.= 'controller/';
        if(is_dir($dir.$controller_path) or is_file($dir.$controller_path.'.php')) {
          // Valid path
          $found = true;
          // The controller must be a file that exists with the search path
          if($c = str_replace('\\', '/', realpath($dir.$controller_path.'.php'))
              and
            is_file($c)
          ) {
            // Set the controller name
            self::$controller = ucfirst(strtolower(EXIDO_ENVIRONMENT_NAME)).'_Controller_'.implode('_', $controller_name);
            self::$controller_view = $controller_path;
            // Set the controller path
            self::$controller_path = $c;
            // Set the method
            $method_segment = $key + 1;
            // Stop searching
            break;
          }
        }
      }
      if($found === false) {
        // Maximum depth has been reached, stop searching
        break;
      }
      // Add another slash
      $controller_path.= '/';
    }

    if($method_segment !== null and isset(self::$rsegments[$method_segment])) {
      // Set method
      if(isset(self::$rsegments[$method_segment])) {
        self::$method = self::$rsegments[$method_segment];
      }

      if(isset(self::$rsegments[$method_segment])) {
        // Set arguments
        self::$arguments = array_slice(self::$rsegments, ($method_segment + 1));
      }
    }

    // If controller does not found
    // We throw the 404 page
    if(self::$controller === null) {
      throw new Exception_Exido_404('Undefined controller %s.', array(self::$current_uri));
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Attempts to determine the current URI using CLI, GET, PATH_INFO, ORIG_PATH_INFO, or PHP_SELF.
   * @return bool
   * @throws Exception_Exido
   */
  public static function getUri()
  {
    // Debug log
    if(Exido::$log_debug) Exido::$log->add('EXIDO_DEBUG_LOG', 'Determine current URI');

    Helper::load('input');

    // Trying to detect the URI
    if(inputServer('PATH_INFO'))
      self::$current_uri = inputServer('PATH_INFO');
    elseif(inputServer('ORIG_PATH_INFO'))
      self::$current_uri = inputServer('ORIG_PATH_INFO');
    elseif(inputServer('PHP_SELF'))
      self::$current_uri = inputServer('PHP_SELF');
    elseif(inputServer('QUERY_STRING'))
      self::$current_uri = inputServer('QUERY_STRING');
    elseif(inputServer('REQUEST_URI'))
      self::$current_uri = inputServer('REQUEST_URI');
    else
      throw new Exception_Exido(__("Can't detect URI"));

    // Remove slashes from the start and end of the URI
    self::$current_uri = trim(self::$current_uri, '/');

    if(self::$current_uri !== '') {
      if($suffix = Exido::config('global.core.url_suffix') and strpos(self::$current_uri, $suffix) !== false) {
        // Remove the URL suffix
        self::$current_uri = preg_replace('#'.preg_quote($suffix).'$#u', '', self::$current_uri);
        // Set the URL suffix
        self::$url_suffix = $suffix;
      }
      if($indexfile = Exido::config('global.core.index_file') and
        $indexpos = strpos(self::$current_uri, $indexfile) and
        $indexpos !== false) {
        // Remove the index file name
        self::$current_uri = substr(self::$current_uri, 0, $indexpos);
      }
      // Reduce multiple slashes into single slashes
      self::$current_uri = preg_replace('#//+#', '/', self::$current_uri);
    }
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the routes.
   * @return object
   */
  public static function getRoutes()
  {
    if(self::$_routes === null) {
      // Load routes
      self::$_routes = Exido::config('route');
    }
    return self::$_routes;
  }

  // ---------------------------------------------------------------------------

  /**
   * Adds a new route to the route object.
   * @param $key
   * @param $value
   * @return void
   */
  public static function addRoute($key, $value)
  {
    self::$_additional_routes[$key] = $value;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a routed segments.
   * @return array
   */
  public static function getSegments()
  {
    return self::$segments;
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a full requested url.
   * @return string
   */
  public static function getUriFull()
  {
    return implode('/', self::$segments);
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns selected uri segment.
   * @param int $num segment number
   * @return string
   */
  public static function getSegment($num)
  {
    if( ! is_numeric($num)) {
      return '';
    }
    $num = $num - 1;
    if(isset(self::$segments[$num])) {
      return self::$segments[$num];
    }
    return '';
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a last segment of the routed uri.
   * @return string
   */
  public static function getLastSegment()
  {
    return end(self::$rsegments);
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns a web-root.
   * @return string
   */
  public static function getWebRoot()
  {
    return self::$web_root;
  }

  // ---------------------------------------------------------------------------

  /**
   * Generates a routed URI from given URI.
   * @param array $segments
   * @return array
   */
  private static function _getRouted(array $segments)
  {
    $ent = Exido::config('global.core.uri_entities');

    if(self::$_routes === null) {
      // Load routes
      self::$_routes = Exido::config('route');
    }
    // Join additional routes
    if( ! empty(self::$_additional_routes)) {
      foreach(self::$_additional_routes as $key => $value) {
        self::$_routes->set($key, $value);
      }
    }
    $segments_string = implode('/', $segments);

    // Is there a literal match? If so we're done
    if(isset(self::$_routes[$segments_string])) {
      return explode('/', self::$_routes[$segments_string]);
    }

    $routed_uri = array();
    // Loop through the routes and see if anything matches
    foreach(self::$_routes as $key => $val) {
      if($key === 'default_controller') {
        continue;
      }
      $key = strtr($key, $ent);
      if(preg_match('#^'.$key.'$#', $segments_string)) {
        // Do we have a back-reference?
        if(strpos($val, '$') !== false and strpos($key, '(') !== false) {
          $val = preg_replace('#^'.$key.'$#', $val, $segments_string);
        }
        // Change a route
        $routed_uri = explode('/', $val);
        break;
      } else {
        // Get the original route
        $routed_uri = $segments;
      }
    }
    return $routed_uri;
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object
   */
  final private function __construct()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }

  // ---------------------------------------------------------------------------

  /**
   * Prevents direct creation of object
   */
  final private function __clone()
  {
    throw new Exception_Exido("The class %s couldn't be instantiated directly", array(__CLASS__));
  }
}

?>