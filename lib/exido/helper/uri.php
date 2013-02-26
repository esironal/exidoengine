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
 * Redirecting.
 * @param string $location
 * @param string $type
 * @param int $timeout
 * @return void
 */
function uriRedirect($location, $type = 'header', $timeout = 3000)
{
  if($type == 'header') {
    header("Location: ".$location);
  } else {
    $output = "<script type=\"text/javascript\">".EXIDO_EOL;
    $output.= "<!-- ".EXIDO_EOL;
    $output.= "setTimeout('location.href(\"".$location."\")', ".$timeout.");".EXIDO_EOL;
    $output.= "//-->".EXIDO_EOL;
    $output.= "</script>".EXIDO_EOL;
    print $output;
  }
}

// -----------------------------------------------------------------------------

/**
 * Gets the site uri.
 * @param $uri
 * @return string
 */
function uriSite($uri)
{
  if(Exido::config('global.core.use_friendly_url')) {
    return HOME.ltrim($uri, '/');
  }
  return HOME.Exido::config('global.core.index_file').'/'.ltrim($uri, '/');
}

// -----------------------------------------------------------------------------

/**
 * Puts a HTTP prefix to uri.
 * @param string $uri
 * @return string
 */
function uriAttachHttp($uri)
{
  if( empty($uri)) {
    return $uri;
  }
  if( ! preg_match('/\:\/\//', $uri)) {
    $uri = 'http://'.$uri;
  }
  return $uri;
}

// -----------------------------------------------------------------------------

/**
 * Returns a routed last uri segment.
 * @return string
 */
function uriLastSegment()
{
  return Router::getLastSegment();
}

// -----------------------------------------------------------------------------

/**
 * Returns an uri segments.
 * @return array
 */
function uriSegments()
{
  return Router::getSegments();
}

// -----------------------------------------------------------------------------

/**
 * Returns a selected uri segment.
 * @param int $num
 * @return string
 */
function uriSegment($num)
{
  return Router::getSegment($num);
}

// -----------------------------------------------------------------------------

/**
 * Returns an uri.
 * @return string
 */
function uriFull()
{
  return uriSite(Router::getUriFull());
}

?>