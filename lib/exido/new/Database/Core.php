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

include_once 'Database/Interface/Adapter.php';
include_once 'Database/Interface/Result.php';
include_once 'Database/Interface/Cache/Read.php';
include_once 'Database/Interface/Cache/Write.php';
include_once 'Database/Abstract.php';
include_once 'Database/Exception.php';
include_once 'Database/Query/Builder.php';
include_once 'Database/Adapter.php';
include_once 'Database/Cache.php';
include_once 'Database/Result.php';
include_once 'Database/Cache/Write.php';
include_once 'Database/Cache/Read.php';
include_once 'Database/Mapper/Result.php';

/**
 * Database connection wrapper.
 * @package    core
 * @subpackage database
 * @copyright  Sharapov A.
 * @created    05/02/2010
 * @version    1.0
 */
final class Exido_Database_Core
{
  // DB instances
  public static $instances = array();

  // Configuration array
  protected static $_config = array
  (
    'benchmark'     => true,
    'pconnect'      => false,
    'character_set' => 'utf8',
    'db_collation'  => 'utf8_general_ci',
    'table_prefix'  => ''
  );

  // ---------------------------------------------------------------------------

  /**
   * Get sa singleton instance.
   * @param string $name
   * @param null $config
   * @return mixed
   * @throws Exception_Exido
   */
  public static function & instance($name = 'default', $config = null)
  {
    if( ! isset(self::$instances[$name])) {
      // Get the DB settings
      if($config == null) {
        $config = @Exido::config('database')->$name;
      }
      if( ! isset($config['type'])) {
        throw new Exception_Exido("DB instance %s doesn't supported by the system", array($name));
      }
      // Merge a default configuration with user's configuration
      self::$_config = array_merge(self::$_config, $config);
      if(is_file(SYSPATH.'Database/Driver/'.ucfirst(self::$_config['type']).'/Adapter.php')) {
        // Initialize a driver
        include_once 'Database/Driver/'.ucfirst(self::$_config['type']).'/Adapter.php';
        $driver = 'Exido_Database_Driver_'.ucfirst(self::$_config['type']).'_Adapter';
        self::$instances[$name] = new $driver(self::$_config);
      } else {
        throw new Exception_Exido("Driver :driver doesn't exist", array('Database/Driver/'.ucfirst(self::$_config['type']).'/Adapter.php'));
      }
    }
    return self::$instances[$name];
  }
}

?>