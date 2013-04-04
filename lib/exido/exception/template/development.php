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

// Unique error identifier
$error_id = uniqid('error');
?>
<html>
<head>
<title><?php print __('General error'); ?></title>
</head>
<body>
<style type="text/css">
#error { background: #ddd; font-size: 1em; font-family:sans-serif; text-align: left; color: #111; }
#error h1,
#error h2 { margin: 0; padding: 1em; font-size: 1em; font-weight: normal; background: #911; color: #fff; }
#error h1 a,
#error h2 a { color: #fff; }
#error h2 { background: #222; }
#error h3 { margin: 0; padding: 0.4em 0 0; font-size: 1em; font-weight: normal; }
#error p { margin: 0; padding: 0.2em 0; }
#error a { color: #1b323b; }
#error pre { overflow: auto; white-space: pre-wrap; }
#error table { width: 100%; display: block; margin: 0 0 0.4em; padding: 0; border-collapse: collapse; background: #fff; }
#error table td { border: solid 1px #ddd; text-align: left; vertical-align: top; padding: 0.4em; }
#error div.content { padding: 0.4em 1em 1em; overflow: hidden; }
#error pre.source { margin: 0 0 1em; padding: 0.4em; background: #fff; border: dotted 1px #b7c680; line-height: 1.2em; }
#error pre.source span.line { display: block; }
#error pre.source span.highlight { background: #f0eb96; }
#error pre.source span.line span.number { color: #666; }
#error ol.trace { display: block; margin: 0 0 0 2em; padding: 0; list-style: decimal; }
#error ol.trace li { margin: 0; padding: 0; }
.js .collapsed { display: none; }
</style>
<script type="text/javascript">
document.documentElement.className = document.documentElement.className + ' js';
function toggle(elem)
{
  elem = document.getElementById(elem);

  if (elem.style && elem.style['display'])
    // Only works with the "style" attr
    var disp = elem.style['display'];
  else if (elem.currentStyle)
    // For MSIE, naturally
    var disp = elem.currentStyle['display'];
  else if (window.getComputedStyle)
    // For most other browsers
    var disp = document.defaultView.getComputedStyle(elem, null).getPropertyValue('display');

  // Toggle the state of the "display" style
  elem.style.display = disp == 'block' ? 'none' : 'block';
  return false;
}
</script>
<div id="error">
  <h1><span class="type"><?php echo $type ?> [ <?php echo $code ?> ]:</span> <span class="message"><?php echo $message; ?></span></h1>

    <div id="<?php echo $error_id ?>" class="content">
        <p><span class="file"><?php echo $file; ?> [ <?php echo $line ?> ]</span></p>
      <?php echo Debug::source($file, $line) ?>
        <ol class="trace">
          <?php foreach(Debug::trace($trace) as $i => $step) : ?>
            <li>
                <p>
                  <span class="file">
                    <?php if ($step['file']): $source_id = $error_id.'source'.$i; ?>
                      <a href="#<?php echo $source_id ?>" onClick="return toggle('<?php echo $source_id ?>')"><?php echo $step['file']; ?> [ <?php echo $step['line'] ?> ]</a>
                    <?php else: ?>
                      {<?php echo 'PHP internal call' ?>}
                    <?php endif ?>
                  </span>
                   &raquo;
                  <?php echo $step['function'] ?>(<?php if ($step['args']): $args_id = $error_id.'args'.$i; ?><a href="#<?php echo $args_id ?>" onClick="return toggle('<?php echo $args_id ?>')"><?php print __('Arguments'); ?></a><?php endif ?>)
                </p>
              <?php if (isset($args_id)): ?>
                <div id="<?php echo $args_id ?>" class="collapsed">
                    <table cellspacing="0">
                      <?php foreach($step['args'] as $name => $arg) : ?>
                        <tr>
                            <td><code><?php echo $name ?></code></td>
                            <td><pre><?php echo Debug::dump($arg) ?></pre></td>
                        </tr>
                      <?php endforeach ?>
                    </table>
                </div>
              <?php endif ?>
              <?php if(isset($source_id)): ?>
                <pre id="<?php echo $source_id ?>" class="source collapsed"><code><?php echo $step['source'] ?></code></pre>
              <?php endif ?>
            </li>
          <?php unset($args_id, $source_id); ?>
          <?php endforeach ?>
        </ol>
    </div>
    <h2><a href="#<?php echo $env_id = $error_id.'environment' ?>" onClick="return toggle('<?php echo $env_id ?>')"><?php print __('Environment'); ?></a></h2>
    <div id="<?php echo $env_id ?>" class="content collapsed">
      <?php $included = get_included_files() ?>
        <h3><a href="#<?php echo $env_id = $error_id.'environment_included' ?>" onClick="return toggle('<?php echo $env_id ?>')"><?php print __('Included files'); ?></a> (<?php echo count($included) ?>)</h3>
        <div id="<?php echo $env_id ?>" class="collapsed">
            <table cellspacing="0">
              <?php foreach($included as $file) : ?>
                <tr>
                    <td><code><?php echo $file; ?></code></td>
                </tr>
              <?php endforeach ?>
            </table>
        </div>
      <?php $included = get_loaded_extensions() ?>
        <h3><a href="#<?php echo $env_id = $error_id.'environment_loaded' ?>" onClick="return toggle('<?php echo $env_id ?>')"><?php print __('Loaded extensions'); ?></a> (<?php echo count($included) ?>)</h3>
        <div id="<?php echo $env_id ?>" class="collapsed">
            <table cellspacing="0">
              <?php foreach($included as $file) : ?>
                <tr>
                    <td><code><?php echo $file; ?></code></td>
                </tr>
              <?php endforeach ?>
            </table>
        </div>
      <?php foreach(array('_SESSION', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER') as $var): ?>
      <?php if(empty($GLOBALS[$var]) or ! is_array($GLOBALS[$var])) continue ?>
        <h3><a href="#<?php echo $env_id = $error_id.'environment'.strtolower($var) ?>" onClick="return toggle('<?php echo $env_id ?>')">$<?php echo $var ?></a></h3>
        <div id="<?php echo $env_id ?>" class="collapsed">
            <table cellspacing="0">
              <?php foreach ($GLOBALS[$var] as $key => $value) : ?>
                <tr>
                    <td><code><?php echo $key; ?></code></td>
                    <td><pre><?php echo Debug::dump($value) ?></pre></td>
                </tr>
              <?php endforeach ?>
            </table>
        </div>
      <?php endforeach ?>
    </div>
</div>
</body>
</html>