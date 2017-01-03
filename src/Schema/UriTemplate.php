<?php


namespace Raml\Schema;

use Psr\Http\Message\UriInterface;

/**
 * Class UriTemplate
 *
 * @package Raml\Schema
 */
class UriTemplate implements UriInterface {

  /** @var array Variables to use in the template expansion */
  protected $variables;

  /** @var array Hash for quick operator lookups */
  private static $operatorHash = [
    ''  => ['prefix' => '',  'joiner' => ',', 'query' => false],
    '+' => ['prefix' => '',  'joiner' => ',', 'query' => false],
    '#' => ['prefix' => '#', 'joiner' => ',', 'query' => false],
    '.' => ['prefix' => '.', 'joiner' => '.', 'query' => false],
    '/' => ['prefix' => '/', 'joiner' => '/', 'query' => false],
    ';' => ['prefix' => ';', 'joiner' => ';', 'query' => true],
    '?' => ['prefix' => '?', 'joiner' => '&', 'query' => true],
    '&' => ['prefix' => '&', 'joiner' => '&', 'query' => true]
  ];

  /** @var array Delimiters */
  private static $delims = [':', '/', '?', '#', '[', ']', '@', '!', '$',
    '&', '\'', '(', ')', '*', '+', ',', ';', '='];

  /** @var array Percent encoded delimiters */
  private static $delimsPct = ['%3A', '%2F', '%3F', '%23', '%5B', '%5D',
    '%40', '%21', '%24', '%26', '%27', '%28', '%29', '%2A', '%2B', '%2C',
    '%3B', '%3D'];

  /**
   * @var mixed
   */
  protected $parsed = [

  ];

  /**
   * @var string
   */
  protected $requestType = "GET";

  /**
   * @param $name
   *
   * @return null|string
   */
  function __get($name) {
    if ($name == "template") {
      return $this->__toString();
    }
    if (isset($this->{$name})) {
      return $this->{$name};
    } elseif (null !== $this->getParsedProperty($name)) {
      return $this->getParsedProperty($name);
    }
    return null;
  }

  /**
   * @param $name
   * @param $value
   */
  function __set($name, $value) {
    if ($name == "template") {
      $this->parsed = parse_url($value);
    } else {
      $this->variables[$name] = $value;
    }
  }


  /**
   * UriTemplate constructor.
   *
   * @param $template
   */
  function __construct($template) {
    $this->parsed = parse_url($template);
  }

  /**
   * @return string
   */
  function __toString() :string {
    $scheme   = isset($this->getParsed()['scheme']) ? $this->getParsed()['scheme'] . '://' : '';
    $host     = isset($this->getParsed()['host']) ? $this->getParsed()['host'] : '';
    $port     = isset($this->getParsed()['port']) ? ':' . $this->getParsed()['port'] : '';
    $user     = isset($this->getParsed()['user']) ? $this->getParsed()['user'] : '';
    $pass     = isset($this->getParsed()['pass']) ? ':' . $this->getParsed()['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($this->getParsed()['path']) ? $this->getParsed()['path'] : '';
    $query    = isset($this->getParsed()['query']) ? '?' . http_build_query($this->getParsed()['query']) : '';
    $fragment = isset($this->getParsed()['fragment']) ? '#' . $this->getParsed()['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
  }

  /**
   * @return mixed
   */
  function &getParsed() {
    return $this->parsed;
  }

  /**
   * @param $property
   *
   * @return null
   */
  function getParsedProperty($property) {
    return isset(  $this->getParsed()[$property] ) ? $this->getParsed()[$property] : null;
  }

  /**
   * @param $property
   * @param $value
   */
  function setParsedProperty($property, $value) {
    $this->parsed[$property] = $value;
  }

  /**
   * @return string
   */
  public function getScheme() : string {
    return $this->getParsedProperty('scheme');
  }

  /**
   * @return null|string
   */
  public function getAuthority() : string {
    $parsed = $this->getParsed();
    return (isset( $parsed['user'] ) && isset( $parsed['pass'] ) ) ? $parsed["user"] . ":" . $parsed['pass'] : null;
  }

  /**
   * @return null
   */
  public function getUserInfo() : string {
    return $this->getParsedProperty('user');
  }

  /**
   * @return null
   */
  public function getHost() : string {
    return $this->getParsedProperty('host');
  }

  /**
   * @return null
   */
  public function getPort() : int {
    return $this->getParsedProperty('port');
  }

  /**
   * @return null
   */
  public function getPath() : string {
    return $this->getParsedProperty('path');
  }

  /**
   * @return null
   */
  public function getQuery() : array {
    return $this->getParsedProperty('query');
  }

  /**
   * @return null
   */
  public function getFragment() : string {
    return $this->getParsedProperty('fragment');
  }

  /**
   * @param string $scheme
   *
   * @return $this
   */
  public function &withScheme($scheme) : \Raml\Schema\UriTemplate {
    $this->getParsed()["scheme"] = $scheme;
    return $this;
  }

  /**
   * @param string $user
   * @param null   $password
   *
   * @return $this
   */
  public function &withUserInfo($user, $password = NULL)  : \Raml\Schema\UriTemplate  {
    $this->getParsed()["user"] = $user;
    $this->getParsed()["pass"] = $password;
    return $this;
  }

  /**
   * @param string $host
   *
   * @return $this
   */
  public function &withHost($host) : \Raml\Schema\UriTemplate  {
    $this->getParsed()['host'] = $host;
    return $this;
  }

  /**
   * @param int|null $port
   *
   * @return $this
   */
  public function &withPort($port)  : \Raml\Schema\UriTemplate  {
    $this->getParsed()['port'] = $port;
    return $this;
  }

  /**
   * @param string $path
   *
   * @return $this
   */
  public function &withPath($path)  : \Raml\Schema\UriTemplate  {
    $this->getParsed()['path'] = $path;
    return $this;
  }

  /**
   * @param string $query
   */
  public function &withQuery($query) : \Raml\Schema\UriTemplate  {
    $this->getParsed()['query'] = $query;
    return $this;
  }

  /**
   * @param string $fragment
   */
  public function &withFragment($fragment) : \Raml\Schema\UriTemplate  {
    $this->getParsed()['fragment'] = $fragment;
    return $this;
  }

  /**
   * @param $name
   * @param $value
   */
  public function setTemplateVariable($name , $value) {
    $this->variables[$name] = $value;
  }

  /**
   * @param $type
   */
  public function setRequestType($type) {
    $this->requestType = $type;
  }

  /**
   * @return string
   */
  public function getRequestType() : string {
    return $this->requestType;
  }

  public function expand(array $variables = null)
  {
    $template = $this->__toString();

    if (false === strpos($template, '{')) {
      return $template;
    }
    if ($variables !== null) {
      $this->variables = $variables;
    }

    return preg_replace_callback(
      '/\{([^\}]+)\}/',
      [$this, 'expandMatch'],
      $template
    );
  }

  /**
   * Parse an expression into parts
   *
   * @param string $expression Expression to parse
   *
   * @return array Returns an associative array of parts
   */
  private function parseExpression($expression)
  {
    $result = [];

    if (isset(self::$operatorHash[$expression[0]])) {
      $result['operator'] = $expression[0];
      $expression = substr($expression, 1);
    } else {
      $result['operator'] = '';
    }

    foreach (explode(',', $expression) as $value) {
      $value = trim($value);
      $varspec = [];
      if ($colonPos = strpos($value, ':')) {
        $varspec['value'] = substr($value, 0, $colonPos);
        $varspec['modifier'] = ':';
        $varspec['position'] = (int) substr($value, $colonPos + 1);
      } elseif (substr($value, -1) === '*') {
        $varspec['modifier'] = '*';
        $varspec['value'] = substr($value, 0, -1);
      } else {
        $varspec['value'] = (string) $value;
        $varspec['modifier'] = '';
      }
      $result['values'][] = $varspec;
    }

    return $result;
  }

  /**
   * Process an expansion
   *
   * @param array $matches Matches met in the preg_replace_callback
   *
   * @return string Returns the replacement string
   */
  private function expandMatch(array $matches)
  {
    static $rfc1738to3986 = ['+' => '%20', '%7e' => '~'];

    $replacements = [];
    $parsed = self::parseExpression($matches[1]);
    $prefix = self::$operatorHash[$parsed['operator']]['prefix'];
    $joiner = self::$operatorHash[$parsed['operator']]['joiner'];
    $useQuery = self::$operatorHash[$parsed['operator']]['query'];

    foreach ($parsed['values'] as $value) {

      if (!isset($this->variables[$value['value']])) {
        continue;
      }

      $variable = $this->variables[$value['value']];
      $actuallyUseQuery = $useQuery;
      $expanded = '';

      if (is_array($variable)) {

        $isAssoc = $this->isAssoc($variable);
        $kvp = [];
        foreach ($variable as $key => $var) {

          if ($isAssoc) {
            $key = rawurlencode($key);
            $isNestedArray = is_array($var);
          } else {
            $isNestedArray = false;
          }

          if (!$isNestedArray) {
            $var = rawurlencode($var);
            if ($parsed['operator'] === '+' ||
              $parsed['operator'] === '#'
            ) {
              $var = $this->decodeReserved($var);
            }
          }

          if ($value['modifier'] === '*') {
            if ($isAssoc) {
              if ($isNestedArray) {
                // Nested arrays must allow for deeply nested
                // structures.
                $var = strtr(
                  http_build_query([$key => $var]),
                  $rfc1738to3986
                );
              } else {
                $var = $key . '=' . $var;
              }
            } elseif ($key > 0 && $actuallyUseQuery) {
              $var = $value['value'] . '=' . $var;
            }
          }

          $kvp[$key] = $var;
        }

        if (empty($variable)) {
          $actuallyUseQuery = false;
        } elseif ($value['modifier'] === '*') {
          $expanded = implode($joiner, $kvp);
          if ($isAssoc) {
            // Don't prepend the value name when using the explode
            // modifier with an associative array.
            $actuallyUseQuery = false;
          }
        } else {
          if ($isAssoc) {
            // When an associative array is encountered and the
            // explode modifier is not set, then the result must be
            // a comma separated list of keys followed by their
            // respective values.
            foreach ($kvp as $k => &$v) {
              $v = $k . ',' . $v;
            }
          }
          $expanded = implode(',', $kvp);
        }

      } else {
        if ($value['modifier'] === ':') {
          $variable = substr($variable, 0, $value['position']);
        }
        $expanded = rawurlencode($variable);
        if ($parsed['operator'] === '+' || $parsed['operator'] === '#') {
          $expanded = $this->decodeReserved($expanded);
        }
      }

      if ($actuallyUseQuery) {
        if (!$expanded && $joiner !== '&') {
          $expanded = $value['value'];
        } else {
          $expanded = $value['value'] . '=' . $expanded;
        }
      }

      $replacements[] = $expanded;
    }

    $ret = implode($joiner, $replacements);
    if ($ret && $prefix) {
      return $prefix . $ret;
    }

    return urldecode($ret);
  }

  /**
   * Determines if an array is associative.
   *
   * This makes the assumption that input arrays are sequences or hashes.
   * This assumption is a tradeoff for accuracy in favor of speed, but it
   * should work in almost every case where input is supplied for a URI
   * template.
   *
   * @param array $array Array to check
   *
   * @return bool
   */
  private function isAssoc(array $array)
  {
    return $array && array_keys($array)[0] !== 0;
  }

  /**
   * Removes percent encoding on reserved characters (used with + and #
   * modifiers).
   *
   * @param string $string String to fix
   *
   * @return string
   */
  private function decodeReserved($string)
  {
    return str_replace(self::$delimsPct, self::$delims, $string);
  }


}