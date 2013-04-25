<?php /*******************************************************************************
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

// Check PHP version
$version = PHP_VERSION;
$version = $version[0].'.'.$version[2].'.'.$version[4];

// Check if app/data folder is writable
$is_writable_appdata = true;
// If we're on a Unix server with safe_mode off we call is_writable
if(DIRECTORY_SEPARATOR == '/' and @ini_get("safe_mode") == false)
  $is_writable_appdata = (bool)is_writable(APPPATH.'data');
// For windows servers and safe_mode "on" installations we'll actually
// write a file then read it. Bah...
if(is_dir(APPPATH.'data')) {
  $file = rtrim(APPPATH.'data', '/').'/'.md5(rand(1,100));
  if(($fp = @fopen(APPPATH.'data', FOPEN_WRITE_CREATE)) === false) {
    $is_writable_appdata = false;
  } else fclose($fp);
  @chmod(APPPATH.'data', DIR_WRITE_MODE);
  @unlink(APPPATH.'data');
  $is_writable_appdata = true;
} elseif(($fp = @fopen(APPPATH.'data', FOPEN_WRITE_CREATE)) === false) {
  $is_writable_appdata = false;
  fclose($fp);
}

$is_accessible_app = false;
$is_accessible_lib = false;

?>
<!DOCTYPE html>
<html>
<head>
<title>ExidoEngine installation script</title>
<meta charset="UTF-8" />
<link rel="shortcut icon" href="css/images/exidoengine.ico" />
<link rel="icon" href="css/images/exidoengine.ico" />
<link rel="stylesheet" type="text/css" href="css/exido-bootstrap/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/exido-bootstrap/bootstrap-green.css" />
<link rel="stylesheet" type="text/css" href="css/install/style.css" />
</head>
<body>
<div class="wrapper">
  <h3>Server configuration</h3>
  <table>
    <tr><th>Module</th><th>Required version</th><th>Your version</th><th>Comments</th></tr>
    <tr><td>PHP</td><td>5.2.4</td><td>
      <?php if(PHP_VERSION_ID < 50204) {
        print '<div class="red">'.$version.'</div>';
      } else {
        print '<div class="green">'.$version.'</div>';
      } ?>
    </td></tr>
  </table>
  <h3>Files and permissions</h3>
  <table>
    <tr><th>Paths</th><th>Status</th><th>Comments</th></tr>
    <tr><td>root:/app/data</td><td>
      <?php if( ! $is_writable_appdata) {
        print '<div class="red">Not writable</div>';
      } else {
        print '<div class="green">Is writable</div>';
      } ?>
    </td><td>Should be writable via script</td></tr>
    <tr><td>root:/app/</td><td>
      <?php if( ! $is_accessible_app) {
      print '<div class="red">Is accessible via WWW</div>';
    } else {
      print '<div class="green">Not accessible via WWW</div>';
    } ?>
    </td><td>Should NOT be accessible via WWW</td></tr>
    <tr><td>root:/lib/</td><td>
      <?php if( ! $is_accessible_lib) {
      print '<div class="red">Is accessible via WWW</div>';
    } else {
      print '<div class="green">Not accessible via WWW</div>';
    } ?>
    </td><td>Should NOT be accessible via WWW</td></tr>
  </table>
  <h3>Database configuration</h3>
  <form method="post" action="">
  <table>
    <tr><td class="db">Host</td><td class="db-field"><input type="text" name="db_host" value="" /></td></tr>
    <tr><td class="db">User</td><td class="db-field"><input type="text" name="db_user" value="" /></td></tr>
    <tr><td class="db">Password</td><td class="db-field"><input type="text" name="db_password" value="" /></td></tr>
    <tr><td class="db">Database name</td><td class="db-field"><input type="text" name="db_name" value="" /></td></tr>
    <tr><td class="db"></td><td class="db-field"><input type="submit" name="submit" value="Continue" /></td></tr>
  </table>
  </form>
</div>
</body>
</html>
<?php die;?>