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

/**
 * Security class.
 * @package    core
 * @copyright  Sharapov A.
 * @created    20/01/2013
 * @version    1.0
 */
final class Security
{
  /**
   * Random hash for protecting URLs
   * @var string
   */
  protected $_xss_hash = '';

  /**
   * List of never allowed strings
   * @var array
   */
  protected $_never_allowed_str = array(
    'document.cookie' => '[removed]',
    'document.write'  => '[removed]',
    '.parentNode'     => '[removed]',
    '.innerHTML'      => '[removed]',
    'window.location' => '[removed]',
    '-moz-binding'    => '[removed]',
    '<!--'            => '&lt;!--',
    '-->'             => '--&gt;',
    '<![CDATA['       => '&lt;![CDATA[',
    '<comment>'       => '&lt;comment&gt;'
  );

  /**
   * List of never allowed regex replacement
   * @var array
   */
  protected $_never_allowed_regex = array(
    'javascript\s*:',
    'expression\s*(\(|&\#40;)', // CSS and IE
    'vbscript\s*:', // IE, surprise!
    'Redirect\s+302',
    "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
  );

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
    return self::$_instance;
  }

  // ---------------------------------------------------------------------------

  /**
   * XSS Cleaner
   *
   * Sanitizes data so that Cross Site Scripting Hacks can be
   * prevented.  This function does a fair amount of work but
   * it is extremely thorough, designed to prevent even the
   * most obscure XSS attempts.  Nothing is ever 100% foolproof,
   * of course, but I haven't been able to get anything passed
   * the filter.
   *
   * Note: This function should only be used to deal with data
   * upon submission.  It's not something that should
   * be used for general runtime processing.
   *
   * This function was based in part on some code and ideas I
   * got from Bitflux: http://channel.bitflux.ch/wiki/XSS_Prevention
   *
   * To help develop this script I used this great list of
   * vulnerabilities along with a few other hacks I've
   * harvested from examining vulnerabilities in other programs:
   * http://ha.ckers.org/xss.html
   *
   * @param mixed $str
   * @return string
   */
  public function cleanXSS($str)
  {
    // Recursive cleaning
    if(is_array($str)) {
      while (list($key) = each($str))
        $str[$key] = $this->cleanXSS($str[$key]);
      return $str;
    }
    Helper::load('string');
    // Remove invisible characters
    $str = stringRemoveInvisibleChars($str);

    // Validate Entities in URLs
    $str = $this->_validateEntities($str);

    // URL Decode
    // Just in case stuff like this is submitted:
    // <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
    // Note: Use rawurldecode() so it does not remove plus signs
    $str = rawurldecode($str);

    // Convert character entities to ASCII
    // This permits our tests below to work reliably.
    // We only convert entities that are within tags since
    // these are the ones that will pose security problems.
    $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, '_convertAttribute'), $str);
    $str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array($this, '_decodeEntity'), $str);

    // Remove Invisible Characters Again!
    $str = stringRemoveInvisibleChars($str);

    // Convert all tabs to spaces
    // This prevents strings like this: ja vascript
    // NOTE: we deal with spaces between characters later.
    // NOTE: preg_replace was found to be amazingly slow here on
    // large blocks of data, so we use str_replace.
    if(strpos($str, "\t") !== false)
      $str = str_replace("\t", ' ', $str);

    // Remove Strings that are never allowed
    $str = $this->_doNeverAllowed($str);

    // Makes PHP/XML tags safe
    $str = str_replace(array('<?', '?'.'>'),  array('&lt;?', '?&gt;'), $str);

    // Compact any exploded words
    // This corrects words like:  j a v a s c r i p t
    // These words are compacted back to their correct state.
    $words = array(
      'javascript', 'expression', 'vbscript', 'script', 'base64',
      'applet', 'alert', 'document', 'write', 'cookie', 'window'
    );

    foreach($words as $word) {
      $temp = '';
      for($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++)
        $temp .= substr($word, $i, 1)."\s*";
      // We only want to do this when it is followed by a non-word character
      // That way valid stuff like "dealer to" does not become "dealerto"
      $str = preg_replace_callback('#('.substr($temp, 0, -3).')(\W)#is', array($this, '_compactExplodedWords'), $str);
    }

    // Remove disallowed Javascript in links or img tags
    // We used to do some version comparisons and use of stripos for PHP5,
    // but it is dog slow compared to these simplified non-capturing
    // preg_match(), especially if the pattern exists in the string
    do {
      $original = $str;
      if(preg_match("/<a/i", $str))
        $str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, '_jsLinkRemoval'), $str);
      elseif(preg_match("/<img/i", $str))
        $str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, '_jsImgRemoval'), $str);
      elseif(preg_match("/script/i", $str) or preg_match("/xss/i", $str))
        $str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $str);
      else {}
    } while($original != $str);
    unset($original);

    // Remove evil attributes such as style, onclick and xmlns
    $str = $this->_removeEvilAttributes($str);

    /*
     * Sanitize naughty HTML elements
     *
     * If a tag containing any of the words in the list
     * below is found, the tag gets converted to entities.
     *
     * So this: <blink>
     * Becomes: &lt;blink&gt;
     */
    $naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
    $str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitizeNaughtyHtml'), $str);

    /*
     * Sanitize naughty scripting elements
     *
     * Similar to above, only instead of looking for
     * tags it looks for PHP and JavaScript commands
     * that are disallowed.  Rather than removing the
     * code, it simply converts the parenthesis to entities
     * rendering the code un-executable.
     *
     * For example:  eval('some code')
     * Becomes:    eval&#40;'some code'&#41;
     */
    $str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);

    // Final clean up
    // This adds a bit of extra precaution in case
    // something got through the above filters
    return $this->_doNeverAllowed($str);
  }

  // --------------------------------------------------------------------

  /**
   * Random Hash for protecting URLs
   * @return string
   */
  public function getHash()
  {
    if($this->_xss_hash == '') {
      mt_srand();
      $this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
    }
    return $this->_xss_hash;
  }

  // --------------------------------------------------------------------

  /**
   * This function is a replacement for html_entity_decode()
   * The reason we are not using html_entity_decode() by itself is because
   * while it is not technically correct to leave out the semicolon
   * at the end of an entity most browsers will still interpret the entity
   * correctly. html_entity_decode() does not convert entities without
   * semicolons, so we are left with our own little solution here. Bummer.
   * @param string $str
   * @param string $charset
   * @return  string
   */
  public function entityDecode($str, $charset = 'UTF-8')
  {
    if(stristr($str, '&') === false)
      return $str;
    $str = html_entity_decode($str, ENT_COMPAT, $charset);
    $str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
    return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
  }

  // --------------------------------------------------------------------

  /**
   * Sanitize naughty HTML
   * Callback function for cleanXSS() to remove naughty HTML elements
   * @param array $matches
   * @return string
   */
  protected function _sanitizeNaughtyHtml(array $matches)
  {
    // Encode opening brace
    $str = '&lt;'.$matches[1].$matches[2].$matches[3];
    // Encode captured opening or closing brace to prevent recursive vectors
    $str.= str_replace(array('>', '<'), array('&gt;', '&lt;'), $matches[4]);
    return $str;
  }

  // --------------------------------------------------------------------

  /*
   * Remove Evil HTML Attributes (like evenhandlers and style)
   * It removes the evil attribute and either:
   * - Everything up until a space
   *   For example, everything between the pipes:
   *   <a |style=document.write('hello');alert('world');| class=link>
   * - Everything inside the quotes
   *   For example, everything between the pipes:
   *   <a |style="document.write('hello'); alert('world');"| class="link">
   * @param string $str The string to check
   * @return string The string with the evil attributes removed
   */
  protected function _removeEvilAttributes($str)
  {
    // All javascript event handlers (e.g. onload, onclick, onmouseover), style, and xmlns
    $evil_attributes = array('on\w*', 'style', 'xmlns', 'formaction');
    do {
      $count = 0;
      $attribs = array();
      // find occurrences of illegal attribute strings without quotes
      preg_match_all('/('.implode('|', $evil_attributes).')\s*=\s*([^\s>]*)/is', $str, $matches, PREG_SET_ORDER);
      foreach($matches as $attr)
        $attribs[] = preg_quote($attr[0], '/');
      // find occurrences of illegal attribute strings with quotes (042 and 047 are octal quotes)
      preg_match_all("/(".implode('|', $evil_attributes).")\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is",  $str, $matches, PREG_SET_ORDER);
      foreach($matches as $attr)
        $attribs[] = preg_quote($attr[0], '/');
      // replace illegal attribute strings that are inside an html tag
      if(count($attribs) > 0)
        $str = preg_replace("/<(\/?[^><]+?)([^A-Za-z<>\-])(.*?)(".implode('|', $attribs).")(.*?)([\s><])([><]*)/i", '<$1 $3$5$6$7', $str, -1, $count);
    } while ($count);
    return $str;
  }

  // ---------------------------------------------------------------------------

  /**
   * Validate URL entities
   * @param string $str
   * @return string
   */
  protected function _validateEntities($str)
  {
    // Protect GET variables in URLs
    // 901119URL5918AMP18930PROTECT8198
    $str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->getHash()."\\1=\\2", $str);
    // Validate standard character entities
    // Add a semicolon if missing. We do this to enable
    // the conversion of entities to ASCII later.
    $str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);
    // Validate UTF16 two byte encoding (x00)
    // Just as above, adds a semicolon if missing.
    $str = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;", $str);
    // Un-Protect GET variables in URLs
    return str_replace($this->getHash(), '&', $str);
  }

  // --------------------------------------------------------------------

  /**
   * Attribute Conversion
   * Used as a callback for XSS Clean
   * @param array $match
   * @return string
   */
  protected function _convertAttribute(array $match)
  {
    return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
  }

  // --------------------------------------------------------------------

  /**
   * HTML Entity Decode Callback
   * Used as a callback for XSS Clean
   * @param array $match
   * @return string
   */
  protected function _decodeEntity(array $match)
  {
    return $this->entityDecode($match[0], strtoupper(__('__charset')));
  }

  // ----------------------------------------------------------------------

  /**
   * Do never allowed
   * A utility function for XSS Clean
   * @param string $str
   * @return string
   */
  protected function _doNeverAllowed($str)
  {
    $str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);
    foreach ($this->_never_allowed_regex as $regex)
      $str = preg_replace('#'.$regex.'#is', '[removed]', $str);
    return $str;
  }

  // ----------------------------------------------------------------

  /**
   * Compact Exploded Words
   * Callback function for cleanXSS() to remove whitespace from
   * things like j a v a s c r i p t
   * @param array $matches
   * @return string
   */
  protected function _compactExplodedWords($matches)
  {
    return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
  }

  // --------------------------------------------------------------------

  /**
   * JS Link Removal
   * Callback function for cleanXSS() to sanitize links
   * This limits the PCRE backtracks, making it more performance friendly
   * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
   * PHP 5.2+ on link-heavy strings
   * @param array $match
   * @return string
   */
  protected function _jsLinkRemoval(array $match)
  {
    return str_replace($match[1],
      preg_replace(
        '#href=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
        '',
        $this->_filterAttributes(str_replace(array('<', '>'), '', $match[1]))
      ),
      $match[0]
    );
  }

  // --------------------------------------------------------------------

  /**
   * JS Image Removal
   * Callback function for cleanXSS() to sanitize image tags
   * This limits the PCRE backtracks, making it more performance friendly
   * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
   * PHP 5.2+ on image tag heavy strings
   * @param array $match
   * @return string
   */
  protected function _jsImgRemoval(array $match)
  {
    return str_replace($match[1],
      preg_replace(
        '#src=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
        '',
        $this->_filterAttributes(str_replace(array('<', '>'), '', $match[1]))
      ),
      $match[0]
    );
  }

  // --------------------------------------------------------------------

  /**
   * Filters tag attributes for consistency and safety
   * @param $str string
   * @return string
   */
  protected function _filterAttributes($str)
  {
    $out = '';
    if(preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches)) {
      foreach($matches[0] as $match)
        $out.= preg_replace("#/\*.*?\*/#s", '', $match);
    }
    return $out;
  }
}

?>