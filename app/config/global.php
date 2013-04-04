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
 * @copyright Copyright (c) 2009 - 2013, ExidoEngine Solutions
 * @link      http://www.exidoengine.com/
 * @since     Version 1.0
 * @filesource
 *******************************************************************************/

return array(
  'core' => array
  (
    'index_file'          => 'index.php',
    'permitted_uri_chars' => 'a-z0-9~%:_\-',
    /**
     * URI entities with regular expressions
     * The entities can be used in application routes (APPPATH/config/route.php)
     * The entity will be replaced with its regular expression.
     */
     'uri_entities'        => array(
                               ':num' => '([0-9]+)',
                               ':abc' => '([A-z]+)',
                               ':sym' => '([A-z0-9-]+)',
                               ':any' => '(.+)'
                             ),
    'url_suffix'          => '.html',
    /**
     * To use friendly URLs you will need to enable apache's mod_rewrite or the
     * same mod for another web-server)
     * and create .htaccess file on the web-root directory with the following contents:
     * RewriteEngine on
     * RewriteBase /
     * RewriteCond %{REQUEST_FILENAME} !-f
     * RewriteCond %{REQUEST_FILENAME} !-d
     * RewriteCond %{REQUEST_URI} !\.(css|gif|ico|jpg|js|png|swf|txt)$
     * RewriteRule ^(.*)$ /index.php/$1 [L]
     *
     * If you don't know how to do this, please just disable this option.
     */
    // TODO: Do the friendly URLs handler
    'use_friendly_url'    => false
  ),
  'date' => array(
    'format_long' => '%e %B %Y, %H:%M'
  )
);

?>