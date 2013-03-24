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
 * The core static class
 * @package    core
 * @copyright  Sharapov A.
 * @created    25/12/2009
 * @version    1.0
 */
final class Exido
{
  /**
   * The final output that will displayed by the system
   * @var string
   */
  public static $output  = '';

  /**
   * Windows environment?
   * @var bool
   */
  public static $is_win  = true;

  /**
   * Is Exido run in command-line environment?
   * @var bool
   */
  public static $is_cli  = false;

  /**
   * Is we get an Xml request
   * @var bool
   */
  public static $is_xml  = false;

  /**
   * Use system debug log
   * @var bool
   */
  public static $log_debug = true;

  /**
   * Use system error log
   * @var bool
   */
  public static $log_error = true;

  /**
   * Config object
   * @var
   */
  public static $config;

  /**
   * Internalization object
   * @var
   */
  public static $i18n;

  /**
   * Log object
   * @var
   */
  public static $log;

  /**
   * Default system charset
   * @var string
   */
  public static $charset = 'UTF-8';

  /**
   * Default system locale
   * @var string
   */
  public static $locale = 'en_US.UTF-8';

  /**
   * Default system time zone
   * @var string
   */
  public static $timezone = 'UTC';

  /**
   * Default database charset
   * @var string
   */
  public static $db_charset = 'utf8';

  /**
   * Default database collation
   * @var string
   */
  public static $db_collation = 'utf8_general_ci';

  /**
   * Default database time names
   * @var string
   */
  public static $db_time_names = 'en_US';

  /**
   * Default database time names
   * @var string
   */
  public static $db_time_zone  = 'UTC';

  /**
   * Default core application name
   * @var string
   */
  public static $core_app_name = 'exidoengine';

  /**
   * Use gZIP compression by default
   * @var
   */
  public static $use_gzip  = false;

  /**
   * Prepend to execution twice
   * @var bool
   */
  protected static $_init  = false;

  /**
   * Include paths that are used to find files
   * @var array
   */
  protected static $_paths = array(APPPATH, SYSPATH);

  /**
   * Included file paths
   * @var array
   */
  protected static $_files = array();

  /**
   * Singleton controller instance
   * @var
   */
  private static $_instance;

  /**
   * Output buffering level
   * @var
   */
  private static $_buffer_level;

  // ---------------------------------------------------------------------------

  /**
   * Initializes the core.
   * @return bool
   */
  public static function initialize()
  {
    if(self::$_init) {
      // Do not allow to execution twice
      return false;
    }
    self::$_init = true;

    // Determine if we are running in a Windows environment
    self::$is_win = (DIRECTORY_SEPARATOR === '\\');
    // Load the logger
    self::$log    = Log::instance();
    // Load the default configuration files
    self::$config = Config::instance()->attach(new Config_File);
    // Load the i18n class
    self::$i18n   = I18n::instance();

    // Enable debug log
    if(self::$log_debug) {
      self::$log->attach(new Log_File(APPPATH.'data/log/debug'), array('EXIDO_DEBUG_LOG'));
      self::$log->add('EXIDO_DEBUG_LOG', 'Initialize framework');
    }

    // Enable error log
    if(self::$log_error) {
      self::$log->attach(new Log_File(APPPATH.'data/log/error'), array('EXIDO_ERROR_LOG'));
    }

    // Determine if we are running in a command line environment
    self::$is_cli = (PHP_SAPI === 'cli');

    // Check if we have an Ajax request
    self::$is_xml = Input::instance()->isXmlRequest();

    // Load helpers
    Helper::load('lang','uri');

    // Check if we can use gZIP compression
    self::$use_gzip =
      (strstr(Input::instance()->server('HTTP_ACCEPT_ENCODING'), "gzip") !== false)
      and
      (extension_loaded("zlib"));

    // Start output buffering
    ob_start(array(__CLASS__, 'outputBuffer'));

    // Save buffering level
    self::$_buffer_level = ob_get_level();

    Event::add('system.routing',   array('Router'   , 'getUri'));
    Event::add('system.routing',   array('Router'   , 'initialize'));
    Event::add('system.execute',   array(__CLASS__  , 'instance'));
    Event::add('system.shutdown',  array(__CLASS__  , 'shutdown'));
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Loads the controller class and instantiate it.
   * @return object
   * @throws Exception_Exido_404
   * @throws Exception_Exido_403
   */
  public static function & instance()
  {
    if(self::$_instance === null) {
      try {
        // Debug log
        if(self::$log_debug) self::$log->add('EXIDO_DEBUG_LOG', 'Include controller class: '.Router::$controller);
        // Start validation of the controller
        include Router::$controller_path;
        $class = new ReflectionClass(Router::$controller);
      } catch (Exception_Exido_404 $e) {
        // Controller does not exist
        throw new Exception_Exido_404('The requested page %s is not found', array(Router::$controller));
      }

      if($class->isAbstract()
          or (IN_PRODUCTION and $class->getConstant('ALLOW_PRODUCTION') == false)
      ) {
        // Controller is not allowed to run in production
        throw new Exception_Exido_403("The controller %s doesn't allowed to run in production", array(Router::$controller));
      }
      // Create a new controller instance
      self::$_instance = $class->newInstance();

      try {
        // Load the controller method
        $method = $class->getMethod(Router::$method);

        // Method exists
        if(Router::$method[0] === '_') {
          // Do not allow access to hidden methods
          throw new Exception_Exido_403("The method %s doesn't allowed to run in production", array(Router::$method));
        }

        if(strstr(Router::$method, 'Controller')) {
          // Do not allow access to special methods
          throw new Exception_Exido_403("The method %s doesn't allowed to run directly", array(Router::$method));
        }

        if($method->isProtected() or $method->isPrivate()) {
          // Do not attempt to invoke protected methods
          throw new Exception_Exido_403("The method %s doesn't allowed to run in production as it is protected or private", array(Router::$method));
        }

        // Default arguments
        $arguments = Router::$arguments;

      } catch (Exception $e) {
        // Use __call instead
        $method = $class->getMethod('__call');
        // Use arguments in __call format
        $arguments = array(Router::$method, Router::$arguments);
      }

      $is_before_fails = false;
      // Execute the "before action" method
      // If the "before action" returns FALSE controller method not executed
      if($class->getMethod('beforeController')->invoke(self::$_instance) === false) {
        $is_before_fails = true;
      } else {
        // Otherwise execute the controller method
        $method->invokeArgs(self::$_instance, $arguments);
      }
      // Execute the "after action" method
      $class->getMethod('afterController')->invoke(self::$_instance);

      if($is_before_fails == false) {
        // Execute the "pushLayout" method
        $class->getMethod('pushLayoutController')->invoke(self::$_instance);
      }
    }
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * Initialize a loaded components.
   * @return void
   */
  public static function initComponents()
  {
    // Debug log
    if(self::$log_debug) self::$log->add('EXIDO_DEBUG_LOG', 'Load components');
    // Read loaded components
    Component::readComponents();
    // Set a new paths array including paths to components
    self::setIncludePaths();
  }

  // ---------------------------------------------------------------------------

  /**
   * Returns the currently active include paths, including the
   * application and system paths.
   * @return array
   */
  public static function getIncludePaths()
  {
    return self::$_paths;
  }

  // ---------------------------------------------------------------------------

  /**
   * Sets an include paths, including the application and system paths.
   * @param array $external_paths
   * @return void
   */
  public static function setIncludePaths(array $external_paths = array())
  {
    // Debug log
    if(self::$log_debug) self::$log->add('EXIDO_DEBUG_LOG', 'Set include paths');

    // Start a new list of include paths, APPPATH first
    self::$_paths   = array(APPPATH);

    // Set the currently environment path
    if(is_dir(APPPATH.'core/'.self::$core_app_name.'/'.strtolower(EXIDO_ENVIRONMENT_NAME).'/'))
      array_push(self::$_paths,
        APPPATH.'core/'.self::$core_app_name.'/'.strtolower(EXIDO_ENVIRONMENT_NAME).'/');

    foreach(array_merge($external_paths, Component::getComponentPaths()) as $key => $path) {
      if(is_dir($path)) {
        // Add the path to include paths
        array_push(self::$_paths, str_replace('\\', '/', realpath($path)).'/');
      } else {
        // This path is invalid, remove it
        unset($external_paths[$key]);
      }
    }

    // Finish the include paths by adding SYSPATH
    array_push(self::$_paths, SYSPATH);
    self::$_paths = array_unique(
      array_merge(
        self::$_paths, explode(':', get_include_path())
      )
    );
    // Debug log
    if(self::$log_debug) self::$log->add('EXIDO_DEBUG_LOG', 'Include paths: '.implode(':', self::$_paths));
  }

  // ---------------------------------------------------------------------------

  /**
   * Finds the path of a file by directory, filename, and extension.
   * If no extension is given, the default extension will be used.
   * @param string $dir
   * @param string $file
   * @param bool $required
   * @param null $ext
   * @return array|bool|string
   * @throws Exception_Exido
   */
  public static function findFile($dir, $file, $required = false, $ext = null)
  {
    // Use the defined extension by default
    $ext = ($ext === null) ? '.php' : '.'.$ext;

    // Create a partial path of the filename
    $dir = str_replace('\\', '/', $dir);
    $pathfile = rtrim($dir, '/').'/'.$file.$ext;

    // Debug log
    if(self::$log_debug) self::$log->add('EXIDO_DEBUG_LOG', 'Include external file /'.ltrim($pathfile, '/'));

    // The file has not been found yet
    $found = false;
    foreach(self::$_paths as $path) {
      if(is_file($path.$pathfile)) {
        if($dir == 'config' or $dir == 'i18n') {
          // A path has been found
          $found[] = $path.$pathfile;
        } else {
          // A path has been found and stop searching
          $found = $path.$pathfile;
          break;
        }
      }
    }

    if(is_array($found))
      $found = array_reverse($found);

    if($found == false) {
      if($required === true) {
        // Invalid file
        throw new Exception_Exido("The required file %s is not found", array($dir.$file.'.php'));
      }
    }
    if($found == true)
      self::$_files[] = $found;

    return $found;
  }

  // ---------------------------------------------------------------------------

  /**
   * Creates a new configuration object for the requested group.
   * @param string $group
   * @return array
   */
  public static function config($group)
  {
    static $config;
    if(strpos($group, '.') !== false) {
      // Split the config group and path
      list($group, $path) = explode('.', $group, 2);
    }

    if( ! isset($config[$group])) {
      // Load the config group into the cache
      $config[$group] = self::$config->load($group);
    }
    if(isset($path)) {
      return arrayPath($config[$group], $path);
    } else {
      return $config[$group];
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Creates a new i18n object for the requested group.
   * @param string $line
   * @return array
   */
  public static function i18n($line)
  {
    static $i18n;

    if( ! isset($i18n[$line]))
      // Load the config group into the cache
      $i18n[$line] = self::$i18n->get($line);

    if( ! empty($i18n[$line]) and $i18n[$line] != $line) {
      return $i18n[$line];
    } else {
      // Check the language parameters. And returns the default
      // values if some does not found.
      if($line == '__charset')     return self::$charset;
      if($line == '__locale')      return self::$locale;
      if($line == '__time_zone')   return self::$timezone;
      if($line == '__db_charset')    return self::$db_charset;
      if($line == '__db_collation')  return self::$db_collation;
      if($line == '__db_time_names') return self::$db_time_names;
      if($line == '__db_time_zone')  return self::$db_time_zone;
      return $line;
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Output handler. Calls during ob_clean, ob_flush, and their variants.
   * @param string $output
   * @return string
   */
  public static function outputBuffer($output)
  {
    // Set and return the final output
    return self::$output = $output;
  }

  // ---------------------------------------------------------------------------

  /**
   * Closes all open output buffers, either by flushing or cleaning,
   * and stores the output buffer for display during shutdown.
   * @param bool $flush disable clear buffers, rather than flushing
   * @return void
   */
  public static function closeBuffers($flush = true)
  {
    if(ob_get_level() >= self::$_buffer_level) {
      // Set the close function
      $close = ($flush === true) ? 'ob_end_flush' : 'ob_end_clean';
      while(ob_get_level() > self::$_buffer_level) {
        // Flush or clean the buffer
        $close();
      }
      // Store the output buffer
      ob_end_clean();
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Triggers the shutdown by closing the output buffer.
   * Run the system.display event.
   * @return void
   */
  public static function shutdown()
  {
    // Debug log
    if(self::$log_debug) self::$log->add('EXIDO_DEBUG_LOG', 'Shutdown the system');

    // Close output buffers
    self::closeBuffers(true);
    // Run the output event
    Event::run('system.display', self::$output);
    // Display the final output
    self::display(self::$output);
  }

  // ---------------------------------------------------------------------------

  /**
   * Prints the global generated output.
   * @param string $output
   * @return void
   */
  public static function display($output)
  {
    // Debug log
    if(self::$log_debug) self::$log->add('EXIDO_DEBUG_LOG', 'Display output');

    if(__('__charset') != 'UTF-8') {
      iconv_set_encoding('internal_encoding', 'UTF-8');
      iconv_set_encoding('output_encoding'  , __('__charset'));
      ob_start('ob_iconv_handler');
    }
    /* TODO: Make the gZIP compression
    if(self::$use_gzip) {
      header("Content-Encoding: gzip");
      print gzencode($output, 2);
    } else {*/
      print $output;
    //}

    // Logger
    self::$log->write();
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