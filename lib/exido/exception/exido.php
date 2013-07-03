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

include_once 'exception/exido/403.php';
include_once 'exception/exido/404.php';

/**
 * Main exido exception class.
 * @package    core
 * @subpackage exception
 * @copyright  Sharapov A.
 * @created    26/10/2011
 * @version    1.0
 */
class Exception_Exido extends Exception
{
  public static $php_errors = array(
    E_ERROR              => 'Fatal Error',
    E_USER_ERROR         => 'User Error',
    E_PARSE              => 'Parse Error',
    E_WARNING            => 'Warning',
    E_USER_WARNING       => 'User Warning',
    E_STRICT             => 'Strict',
    E_NOTICE             => 'Notice',
    E_RECOVERABLE_ERROR  => 'Recoverable Error',
  );

  public static $codes = array(
    '500',
    '404',
    '403'
  );

  // ---------------------------------------------------------------------------

  /**
   * Constructor. By default it throws the internal server error (Code 500).
   * @param string $message
   * @param array $vars
   * @param int $code
   */
  public function __construct($message = '', array $vars = array(), $code = 500)
  {
    if(defined('E_DEPRECATED'))
      // E_DEPRECATED only exists in PHP >= 5.3.0
      self::$php_errors[E_DEPRECATED] = 'Deprecated';

    // Save the unmodified code
    // @link http://bugs.php.net/39615
    $this->code = $code;

    // Pass the message and integer code to the parent
    parent::__construct(vsprintf(__($message), $vars), (int)$code);
  }

  // ---------------------------------------------------------------------------

  /**
   * Magic object-to-string method.
   *
   *     echo $exception;
   *
   * @return string|void
   */
  public function __toString()
  {
    return self::text($this);
  }

  // ---------------------------------------------------------------------------

  /**
   * Error handler.
   * @param int $errno
   * @param string $errstr
   * @param string $errfile
   * @param int $errline
   * return void
   */
  public static function handlerError($errno, $errstr, $errfile, $errline)
  {
    if(IN_PRODUCTION == false) {
      // Get the handler view file
      $view_file = Exido::findFile('exception/template', 'error');
      if($view_file) {
        include_once $view_file;
      } else {
        // Or print an error directly
        echo sprintf(self::$php_errors[$errno].' [ %s ]: %s ~ %s [ %d ]', $errno, strip_tags($errstr), Debug::path($errfile), $errline)."<br />".EXIDO_EOL;
      }
    }
    Exido::$log->add('EXIDO_ERROR_LOG', sprintf(__('Error').' [ %s ]: %s ~ %s [ %d ]', $errno, strip_tags($errstr), Debug::path($errfile), $errline));
  }

  // ---------------------------------------------------------------------------

  /**
   * Inline exception handler, displays the error message, source of the
   * exception, and the stack trace of the error.
   * @param object $e
   * return void
   */
  public static function handlerException($e)
  {
    try {
      // Get the exception information
      $type    = get_class($e);
      $code    = $e->getCode();
      $message = $e->getMessage();
      $file    = $e->getFile();
      $line    = $e->getLine();

      if( ! in_array($code, self::$codes))
        $code = 500;

      // Get the exception backtrace
      $trace = $e->getTrace();

      // Run logger
      self::log($e);

      // If Exido running in a command line environment
      // or using an XML request.
      // We just print a json encoded string.
      if(Exido::$is_cli or Exido::$is_xml) {
        // Just display the text of the exception
        exit(json_encode(array(
          'status' => false,
          'code'   => $code,
          'text'   => $message
        )));
      }

      if( ! headers_sent()) {
        // Make sure the proper http header is sent
        header('Content-Type: text/html; charset='.__('__charset'), true, $code);
      }

      // If we're in production so we should return the correct error page.
      if(IN_PRODUCTION == true) {
        if($e instanceof Exception_Database) {
          exit($message);
        } else {
          $view          = View::instance();
          $view->code    = $code;
          $view->message = $message;
          $view->file    = Debug::path($file);
          $view->line    = $line;
          $html = Registry::factory('View_Exception')
                  ->setLayout('exception/template', 'error'.$code)
                  ->load()
                  ->parse($view, new View_Helper);
          // Display the contents and exit
          exit($html);
        }
      } else {

        if($e instanceof Exception_Database) {
          $message = $e->errstr.' [ '.$e->errquery.' ]';
        }

        // Return the page with more information about error in the development mode.
        include_once Exido::findFile('exception/template', 'development');
        exit(1);
      }
    } catch (Exception $e) {
      // Clean the output buffer if one exists
      ob_get_level() and ob_clean();
      // Display the exception text
      echo self::text($e), EXIDO_EOL;
      // Run logger
      Exido::$log->write();
      // Exit with an error status
      exit(1);
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets a single line of text representing the exception:
   * Error [ Code ]: Message ~ File [ Line ]
   * @param Exception $e
   * @return string
   */
  public static function text(Exception $e)
  {
    return sprintf('%s [ %s ]: %s ~ %s [ %d ]',
      get_class($e), $e->getCode(), strip_tags($e->getMessage()), Debug::path($e->getFile()), $e->getLine());
  }

  // ---------------------------------------------------------------------------

  /**
   * Write log.
   * @param Exception $e
   * @return void
   */
  public static function log(Exception $e)
  {
    if($e instanceof Exception_Database) {
      Exido::$log->add('EXIDO_ERROR_LOG', $e->errstr.' [ '.$e->errquery.' ]');
    } else {
      Exido::$log->add('EXIDO_ERROR_LOG', self::text($e));
    }
    Exido::$log->write();
  }
}

?>