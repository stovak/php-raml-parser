<?php

namespace Raml\Config;

/**
 *
 */
define("APIDEFINITION_FILE_LOADER_CLASS", "\\Raml\\FileLoader\\DefaultFileLoader");

/**
 *
 */
define("APIDEFINITION_EXAMPLE_ITERATOR_CLASS", "\\Raml\\Example\\DefaultExampleIterator");

/**
 *
 */
define("APIDEFINITION_REQUEST_DEFAULTS_CLASS", "\\Raml\\Schema\\UriTemplate");


/**
 * Class ParseConfiguration
 *
 * @package Raml
 */
class ParseConfiguration {
  /**
   * If directory tree traversal is allowed
   * Enabling this may be a security risk!
   *
   * @var boolean
   */
  private $allowDirectoryTraversal = FALSE;

  /**
   * Should schemas be parsed
   * This is most likely wanted, but does increase time
   *
   * @var boolean
   */
  private $parseSchemas = TRUE;

  /**
   * Should security schemes be merged
   *
   * @var boolean
   */
  private $parseSecuritySchemes = TRUE;
  /**
   * @var string
   */
  private $fileLoaderClassName = APIDEFINITION_FILE_LOADER_CLASS;

  /**
   * @var string
   */
  private $exampleIteratorClassName = APIDEFINITION_EXAMPLE_ITERATOR_CLASS;

  /**
   * @var string
   */
  private $requestDefaultsClassName = APIDEFINITION_REQUEST_DEFAULTS_CLASS;


  // ----

  /**
   * Enable directory traversal
   */
  public function enableDirectoryTraversal() {
    $this->allowDirectoryTraversal = TRUE;
  }

  /**
   * Disable directory traversal
   */
  public function disableDirectoryTraversal() {
    $this->allowDirectoryTraversal = FALSE;
  }

  /**
   * If directory tree traversal is allowed
   *
   * @return boolean
   */
  public function isDirectoryTraversalAllowed() {
    return $this->allowDirectoryTraversal;
  }

  // ---

  /**
   * Enable schema parsing
   */
  public function enableSchemaParsing() {
    $this->parseSchemas = TRUE;
  }

  /**
   * Disable schema parsing
   */
  public function disableSchemaParsing() {
    $this->parseSchemas = FALSE;
  }

  /**
   * Is schema parsing enabled
   *
   * @return boolean
   */
  public function isSchemaParsingEnabled() {
    return $this->parseSchemas;
  }

  // ---

  /**
   * Enable security scheme parsing
   */
  public function enableSecuritySchemeParsing() {
    $this->parseSecuritySchemes = TRUE;
  }

  /**
   * Disable security scheme parsing
   */
  public function disableSecuritySchemeParsing() {
    $this->parseSecuritySchemes = FALSE;
  }

  /**
   * Is security scheme parsing enabled
   *
   * @return boolean
   */
  public function isSchemaSecuritySchemeParsingEnabled() {
    return $this->parseSecuritySchemes;
  }

  /**
   * @return string
   */
  public function getFileLoaderClassName() {
    return $this->fileLoaderClassName;
  }

  /**
   * @param $name
   */
  public function setFileLoaderClassName($name) {
    $this->fileLoaderClassName = $name;
  }

  /**
   * @return string
   */
  public function getExampleIteratorClassName() {
    return $this->exampleIteratorClassName;
  }

  /**
   * @param $name
   */
  public function setExampleIteratorClassName($name) {
    $this->exampleIteratorClassName = $name;
  }


  /**
   * @return string
   */
  public function getRequestDefaultsClassName() {
    return $this->requestDefaultsClassName;
  }

  /**
   * @param string $requestDefaultsClassName
   */
  public function setRequestDefaultsClassName($requestDefaultsClassName) {
    $this->requestDefaultsClassName = $requestDefaultsClassName;
  }



}
