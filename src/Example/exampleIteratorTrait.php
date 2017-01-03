<?php


namespace Raml\Example;

use Prophecy\Exception\Doubler\ClassNotFoundException;
use Symfony\Component\Yaml\Yaml;


/**
 * Class exampleIteratorTrait
 *
 * @package Raml\Example
 */
trait exampleIteratorTrait {

  /**
   * @var \ReflectionClass $requestDefaultsReflector
   */
  protected $requestDefaultsReflector;

  /**
   * @var \Iterator $examples
   */
  private $examples;

  /**
   * @var \ReflectionClass
   */
  protected $exampleIterator;

  /**
   * @param null $className
   *
   * @throws \Exception
   */
  public function setExampleIteratorClassName($className = null) {
    if ($className == null) {
      $className = "\\ArrayIterator";
    }
    if (class_exists($className)) {
      $this->exampleIterator = new \ReflectionClass($className);
      if (!in_array( "Iterator", array_keys($this->exampleIterator->getInterfaces()))) {
        throw new \Exception(sprintf("ExampleIterator class does not implement Iterator:%s", print_r(array_keys($this->exampleIterator->getInterfaces()), true)));
      }
    } else {
      throw new \Exception(sprintf("Class does not exist: %s", $className));
    }
  }

  /**
   * @return null|string
   */
  public function getExampleIteratorClassName(){
    if ($this->exampleIterator instanceOf \ReflectionClass) {
      return $this->exampleIterator->getName();
    } else {
      return null;
    }
  }

  /**
   * @param array $examples
   * @param       $apiDefinition
   * @param       $requestDefaults
   *
   * @return object
   */
  public function getExampleIterator($examples = []) {
    if ($this->exampleIterator instanceof \ReflectionClass) {
      return $this->exampleIterator->newInstanceArgs([ $examples, $this->getRequestDefaults() ]);
    }
    else return null;
  }

  /**
   * @param null|string         $data            either string or some SplFileObject which the __toString() method
   *                                             resolves to a real path of the datafile
   *
   * @param \Raml\ApiDefinition $apiDefinition
   */
  function setExamples($data = null, \Raml\ApiDefinition &$apiDefinition) {
    $this->setExampleIteratorClassName($apiDefinition->getExampleIteratorClassName());
    if (is_string($data)) {
      $data = trim(str_replace("!include", "", $data));
      $file = $apiDefinition->getFileLoader()->loadFile($data);
      if ($file != null and file_exists($file)) {
        $this->examples = $this->getExampleIterator(Yaml::parse(file_get_contents($file)));
      }
    }
    //TODO: handle explicit examples
  }

  /**
   * @return \Iterator
   */
  function getExamples() {
    return $this->examples;
  }

  /**
   * @return \Raml\Config\RequestDefaults
   */
  function getRequestDefaults() {
    if (!($this->requestDefaultsReflector instanceof \ReflectionClass)) {
      $this->setRequestDefaultsClassName();
    }
    $toReturn = $this->requestDefaultsReflector->newInstanceArgs([ $this->getUri() ]);
    if (method_exists($this, "getType")) {
      $toReturn->setRequestType($this->getType());
    }
    if (method_exists($this, "getQueryParameters")) {
      $queryDefaults = [];
      foreach ($this->getQueryParameters() as $name => $paramInfo) {
        if ($paramInfo->isRequired()) {
          $queryDefaults[$name] = $paramInfo->getExample();
        }
      }
    }
    $toReturn->withQuery($queryDefaults);
    return $toReturn;
  }

  /**
   * @param string $requestDefaultsClass
   */
  function setRequestDefaultsClassName($requestDefaultsClass = "Raml\\Schema\\UriTemplate") {
    if (class_exists($requestDefaultsClass)) {
      $this->requestDefaultsReflector = new \ReflectionClass($requestDefaultsClass);
    } else {
      throw new ClassNotFoundException($requestDefaultsClass);
    }
  }

  function getRequestDefaultsClassName() {
    return $this->requestDefaultsReflector->getName();
  }

}