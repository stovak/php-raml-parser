<?php


namespace Raml\Config;

define("RAML_CONFIG_REQUEST_DEFAULTS_REQUEST_CLASS", "\\Psr7\\Request");


/**
 * Class RequestDefaults
 *
 * @package Raml
 */
class RequestDefaults {

  /**
   * @var string $uri
   */
  protected $uri;

  /**
   * @var string $requestType
   */
  protected $requestType;

  /**
   * @var array
   */
  protected $queryDefaults = [];

  function __construct() {

  }

  /**
   * @return string
   */
  public function getUri() {
    return $this->uri;
  }

  /**
   * @param string $uri
   */
  public function setUri(\Psr\Http\Message\UriInterface $uri) {
    $this->uri = $uri;
  }

  /**
   * @return string
   */
  public function getRequestType() {
    return $this->requestType;
  }

  /**
   * @param string $requestType
   */
  public function setRequestType($requestType) {
    $this->requestType = $requestType;
  }

  /**
   * @param $defaults
   */
  public function setQueryDefaults($defaults) {
    $this->queryDefaults = $defaults;
  }

  /**
   * @return array
   */
  public function getQueryDefaults() {
    return $this->queryDefaults;
  }

}