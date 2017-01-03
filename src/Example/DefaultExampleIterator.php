<?php


namespace Raml\Example;
use Iterator;
use Psr\Http\Message\UriInterface;


/**
 * Class DefaultExampleIterator
 *
 * @package Raml\Example
 */
class DefaultExampleIterator extends \AppendIterator {

  /**
   * @var array $items
   */
  protected $items = [];

  /**
   * @var integer $pointer
   */
  protected $pointer = 0;

  /**
   * @var \Raml\Config\RequestDefaults $requestDefaults
   */
  protected $requestDefaults;

  /**
   * DefaultExampleIterator constructor.
   *
   * @param                              $items
   * @param \Raml\ApiDefinition          $apiDefinition
   * @param \Raml\Config\RequestDefaults $requestDefaults
   */
  function __construct($items = [], UriInterface $requestDefaults = null) {
    if ($requestDefaults !== null) {
      $this->requestDefaults = $requestDefaults;
    }
    $this->append($items);
  }

  /**
   * @return mixed
   */
  public function current() {
    if ($this->valid()) {
      return $this->items[array_keys($this->items)[$this->pointer]];
    }
    else return null;
  }

  /**
   * @return mixed
   */
  public function next() {
    $this->pointer++;
    return $this->current();
  }

  /**
   * @return integer
   */
  public function key() {
    return array_keys($this->items)[$this->pointer];
  }

  /**
   * @return bool
   */
  public function valid() {

    return isset(array_keys($this->items)[$this->pointer])
      && isset($this->items[array_keys($this->items)[$this->pointer]]);
  }

  /**
   * @return
   */
  public function rewind() {
    $this->pointer = 0;
    return $this->current();
  }

  /**
   * @return \Psr\Http\Message\UriInterface|\Raml\Config\RequestDefaults
   */
  public function &getRequestDefautls() {
    return $this->requestDefaults;
  }

  /**
   * @param $key
   * @return mixed
   */
  function getKeyed($key) {
    if (is_string($key) && isset($this->items[$key])) {
      return $this->items[$key];
    } elseif (is_numeric($key) && is_string(array_keys($this->items)[0])) {
      return $this->items[array_keys($this->items)[$key]];
    } else {
      return null;
    }
  }

  function append($items) {
    if (is_array($items)) {
      $this->.
    }
  }

}