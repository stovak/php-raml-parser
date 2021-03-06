<?php

namespace Raml\FileLoader;

use JsonSchema\Uri\UriRetriever;
use JsonSchema\RefResolver;
use Raml\Exception\InvalidJsonException;

/**
 * Fetches JSON schema as a string, included refs
 */
class JsonSchemaFileLoader implements FileLoaderInterface
{
    /**
     * @var string[]
     */
    private $validExtensions;

    /**
     * @param string[] $validExtensions
     */
    public function __construct($validExtensions = ['json'])
    {
        $this->validExtensions = $validExtensions;
    }

    /**
     * Load a json from a path and resolve references
     *
     * @param string $filePath
     *
     * @throws \Exception
     *
     * @return string
     */
    public function loadFile($filePath)
    {
        $retriever = new UriRetriever;
        $jsonSchemaParser = new RefResolver($retriever);
        try {
            $json = $jsonSchemaParser->fetchRef('file://' . (string) $filePath, null);
        } catch (\Exception $e) {
            $json = json_decode(file_get_contents( (string) $filePath));
        }

        try {
            return json_encode($json);
        } catch (\Exception $e) {
            throw new InvalidJsonException(json_last_error());
        }
    }

    /**
     * Get the list of supported extensions
     *
     * @return string[]
     */
    public function getValidExtensions()
    {
        return $this->validExtensions;
    }
}
