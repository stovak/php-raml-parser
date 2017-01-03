<?php


namespace Raml\FileLoader;


trait fileLoaderTrait {


  /**
   * @var \ReflectionClass
   */
  protected $fileLoader = null;


  /**
   * @param null $fileLoaderClass
   *
   * @throws \Exception
   */
  public function setFileLoaderClassName($fileLoaderClass = null) {
    if ($fileLoaderClass == null) {
      $fileLoaderClass = APIDEFINITION_FILE_LOADER_CLASS;
    }
    if (class_exists($fileLoaderClass)) {
      $this->fileLoader = new \ReflectionClass($fileLoaderClass);
      if (!in_array( "Raml\\FileLoader\\FileLoaderInterface", array_keys($this->fileLoader->getInterfaces()))) {
        throw new \Exception(sprintf("File Loader class does not implement FileLoaderInterface:%s", print_r(array_keys($this->fileLoader->getInterfaces()), true)));
      }
    } else {
      throw new \Exception(sprintf("FileLoader Class Does Not Exist:%s", $fileLoaderClass));
    }
  }


  /**
   * @return \Raml\FileLoader\FileLoaderInterface
   */
  public function getFileLoader() {
    if ($this->fileLoader == null) {
      $this->setExampleIterator();
    }
    return $this->fileLoader->newInstance();
  }

}