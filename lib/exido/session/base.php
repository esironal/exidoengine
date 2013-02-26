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
 * Session class.
 * @package    core
 * @subackage  session
 * @copyright  Sharapov a.
 * @created    22/04/2010
 * @version    1.0
 */
abstract class Session_Base
{
  public $cookie_name    = 'exido_session_id';
  public $life_time      = 3000;
  public $update_time    = 300;
  public $db_table_name  = 'session';
  public $cookie_path    = '';
  public $cookie_domain  = '';
  public $time_reference = 'time';

  public $sessiondata    = array();
  public $use_phpsession = false;
  public $use_database   = false;
  public $sess_dir_name  = 'e-sess';
  public $sess_file_pref = 's-';
  public $sess_file_suff = '.dsess';
  public $db_files_path;
  public $input;
  public $now;

  // ---------------------------------------------------------------------------

  /**
   * Constructor.
   * @throws Exception_Exido
   */
  public function __construct()
  {
    $this->input = Input::instance();

    // Set the "now" time. can either be gmt or server time, based on the
    // config prefs.  we use this to set the "last activity" time
    $this->now = $this->_getTime();

    $config = Exido::config('session');
    // Set all the session preferences via the config file
    foreach(array('cookie_name',
                  'life_time',
                  'use_database',
                  'use_phpsession',
                  'db_table_name',
                  'db_files_path',
                  'cookie_path',
                  'cookie_domain',
                  'time_reference') as $key) {
      $this->$key = $config[$key];
    }

    // We dont make anything else if we use the PHP sessions
    if($this->use_phpsession) {
      @session_start();
      return;
    }

    if(empty($this->cookie_domain)) {
      $this->cookie_domain = HOST;
    }

    if(empty($this->db_table_name)) {
      $this->db_table_name = 'session';
    }

    if(empty($this->db_files_path)) {
      $this->db_files_path = APPPATH.'data/cache';
    }

    $this->db_files_path = rtrim($this->db_files_path, '/').'/'.$this->sess_dir_name.'/';

    // Try to create session directory
    if( ! is_dir($this->db_files_path)) {
      if( ! @mkdir($this->db_files_path, DIR_WRITE_MODE, true)) {
        throw new Exception_Exido("Couldn't create session directory");
      }
    }

    // Load a database instance
    if($this->use_database) {
      $this->use_database = Registry::factory('Session_Db');
      $this->use_database->setDbTableName($this->db_table_name);
    }

    // Run the session routine. If a session doesn't exist we'll
    // create a new one. If it does, we'll update it.
    if( ! $this->_read()) {
      $this->_create();
    } else {
      $this->_update();
    }
  }

  // -----------------------------------------------------------------------------

  /**
   * Fetches the current session data if it exists
   * @return bool
   */
  private function _read()
  {
    $result = false;

    // Fetch the cookie and get a session id
    $sessid = $this->input->cookie($this->cookie_name);
    // no cookie?  goodbye cruel world!...
    if(empty($sessid)) {
      return false;
    }
    // Is we find an exido session ID?
    if( ! is_string($sessid)) {
      $this->destroy($sessid);
      return false;
    }

    // Get session data from database or file
    if($this->use_database) {
      if($result = $this->use_database->getUserData
      (
        $sessid,
        $this->input->ip(),
        trim(substr($this->input->useragent(), 0, 50))
      ));
    } else {
      $p = $this->db_files_path.$this->sess_file_pref.$sessid.$this->sess_file_suff;
      if(is_file($p)) {
        if($file = file_get_contents($p)) {
          $result = $this->_unserialize($file);
        }
      }
    }
    // No result?  kill it!
    if( ! is_array($result)) {
      $this->destroy($sessid);
      return false;
    }

    // Is the session current?
    if(($result['last_activity'] + $this->life_time) < $this->now) {
      $this->destroy($sessid);
      return false;
    }
    $this->sessiondata = $result;
    if($this->use_database) {
      $this->sessiondata['data'] = $this->_unserialize($this->sessiondata['data']);
    }
    unset($result);
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Creates a new user session.
   * @return void
   * @throws Exception_Exido
   */
  private function _create()
  {
    // Generates a session id
    // to make the session id even more secure we'll combine it with the user's ip
    $sessid = mt_rand(0, mt_getrandmax()).$this->input->ip();

    $this->sessiondata = array
    (
      'session_id'    => md5(uniqid($sessid, true)),
      'ip_address'    => $this->input->ip(),
      'user_agent'    => substr($this->input->useragent(), 0, 50),
      'last_activity' => $this->now,
      'data' => array()
    );

    // Save the data to the database if needed
    if($this->use_database) {
      $this->use_database->setData($this->sessiondata, $this->_serialize($this->sessiondata['data']));
    } else {
      if( ! @file_put_contents($this->db_files_path
                               .$this->sess_file_pref
                               .$this->sessiondata['session_id']
                               .$this->sess_file_suff,
                                $this->_serialize($this->sessiondata)
                               )
      ) {
        throw new Exception_Exido("Couldn't save session file");
      }
    }
    // Write the cookie
    $this->_setcookie();
  }

  // ---------------------------------------------------------------------------

  /**
   * Updates a session.
   * @return void
   * @throws Exception_Exido
   */
  private function _update()
  {
    // We only update the session every five minutes by default
    if(($this->sessiondata['last_activity'] + $this->update_time) >= $this->now) {
      return;
    }
    // Update session data
    if($this->use_database) {
      $this->use_database->updateActivityTime($this->now, $this->sessiondata['session_id']);
    } else {
      // Try to read a session file
      if($file = file_get_contents($this->db_files_path
                                    .$this->sess_file_pref
                                    .$this->sessiondata['session_id']
                                    .$this->sess_file_suff
                                    )
      ) {
        $file = $this->_unserialize($file);
        $file['last_activity'] = $this->now;
        // Update a session file
        if( ! @file_put_contents($this->db_files_path
                                 .$this->sess_file_pref
                                 .$file['session_id']
                                 .$this->sess_file_suff,
                                 $this->_serialize($file)
                                 )
        ) {
          throw new Exception_Exido("Couldn't save session file");
        }
      } else {
        throw new Exception_Exido("Couldn't open session file");
      }
    }
    // Write the cookie
    $this->_setcookie();
  }

  // ---------------------------------------------------------------------------

  /**
   * Writes a session variable.
   * @param string $key
   * @param mixed $value
   * @return bool
   */
  public function set($key, $value)
  {
    if($this->use_phpsession) {
      if( ! isset($_SESSION))
        @session_start();
      $_SESSION[$key] = $value;
      return true;
    }
    if( ! empty($key) and ! empty($value)) {
      $this->sessiondata['data'][$key] = $value;
    }
    $this->_write();
    return true;
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the session value by key.
   * @param string $key
   * @return bool|mixed
   */
  public function get($key)
  {
    if($this->use_phpsession) {
      if( ! isset($_SESSION)) {
        @session_start();
      }
      if(isset($_SESSION[$key])) {
        return $_SESSION[$key];
      }
      return false;
    }

    if( ! isset($this->sessiondata['data'][$key])) {
      return false;
    }
    return $this->sessiondata['data'][$key];
  }

  // ---------------------------------------------------------------------------

  /**
   * Writes a session data.
   * @return void
   * @throws Exception_Exido
   */
  private function _write()
  {
    if($this->use_database) {
      // Run the update query
      $this->use_database->updateUserData(
        $this->sessiondata['session_id'],
        $this->sessiondata['last_activity'],
        $this->_serialize($this->sessiondata['data'])
      );
    } else {
      if($file = file_get_contents($this->db_files_path
                                    .$this->sess_file_pref
                                    .$this->sessiondata['session_id']
                                    .$this->sess_file_suff)
      ) {
        $file = $this->_unserialize($file);
        $file['last_activity'] = $this->sessiondata['last_activity'];
        $file['data']          = $this->sessiondata['data'];
        if( ! @file_put_contents($this->db_files_path
                                 .$this->sess_file_pref
                                 .$file['session_id']
                                 .$this->sess_file_suff,
                                 $this->_serialize($file)
                                 )
        ) {
          throw new Exception_Exido("Couldn't save session file");
        }
      } else {
        throw new Exception_Exido("Couldn't open session file");
      }
    }

    // Write the cookie.  notice that we manually pass the cookie data array to the
    // _set_cookie() function. normally that function will store $this->userdata, but
    // in this case that array contains custom data, which we do not want in the cookie.
    $this->_setcookie();
  }

  // ---------------------------------------------------------------------------

  /**
   * Writes the session cookie.
   * @return  void
   * @throws Exception_Exido
   */
  private function _setcookie()
  {
    // Set the cookie
    if( ! setcookie(
      $this->cookie_name,
      $this->sessiondata['session_id'],
      $this->life_time + $this->now,
      $this->cookie_path,
      $this->cookie_domain,
      0
    )) {
      throw new Exception_Exido("Couldn't set session cookie");
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Serialize an array. The function first converts any slashes found in the array to a temporary
   * marker, so when it gets unserialized the slashes will be preserved.
   * @param mixed $data
   * @return string
   */
  private function _serialize($data)
  {
    if(is_array($data)) {
      foreach ($data as $key => $val) {
        $data[$key] = str_replace('\\', '{{slash}}', $val);
      }
    } else {
      $data = str_replace('\\', '{{slash}}', $data);
    }
    return serialize($data);
  }

  // ---------------------------------------------------------------------------

  /**
   * Unserialize an array. The function unserializes a data string, then converts any
   * temporary slash markers back to actual slashes
   *
   * @param string $data
   * @return array|mixed
   */
  private function _unserialize($data)
  {
    $data = unserialize(stripslashes($data));
    if(is_array($data)) {
      foreach($data as $key => $val) {
        $data[$key] = str_replace('{{slash}}', '\\', $val);
      }
      return $data;
    }
    return str_replace('{{slash}}', '\\', $data);
  }


  // ---------------------------------------------------------------------------

  /**
   * Destroy the current session.
   * @param string $sessid
   * @return void
   */
  public function destroy($sessid = '')
  {
    if($sessid == '' and isset($this->sessiondata['session_id'])) {
      $sessid = $this->sessiondata['session_id'];
    }

    if($this->use_phpsession) {
      @session_destroy();
      return;
    }
    if($this->use_database) {
      // kill the session db row
      $this->use_database->deleteData($sessid);
    } else {
      @unlink($this->db_files_path.$this->sess_file_pref.$sessid.$this->sess_file_suff);
    }

    // kill the cookie
    setcookie(
      $this->cookie_name,
      '',
      ($this->now - 31500000),
      $this->cookie_path,
      $this->cookie_domain,
      0
    );
  }

  // ---------------------------------------------------------------------------

  /**
   * Gets the "now" time.
   * @return int
   */
  private function _getTime()
  {
    if(strtolower($this->time_reference) == 'gmt') {
      $time = dateConvert2Gmt();
    } else {
      $time = time();
    }
    return $time;
  }
}

?>