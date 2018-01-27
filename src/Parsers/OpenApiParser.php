<?php

namespace Juddling\OpenApiLaravel\Parsers;

use Juddling\OpenApiLaravel\Model;
use Symfony\Component\Yaml\Yaml;

class OpenApiParser
{
    private $file;
    private $spec;

    public function __construct($file)
    {
        $this->file = $file;
        $this->spec = Yaml::parseFile($this->file);
    }

    /**
     * @return Model[]
     */
    public function models(): array
    {
        $components = $this->spec['components'];

        if (!array_key_exists('schemas', $components)) {
            throw new \RuntimeException("No models found in components.schemas");
        }

        $models = $this->parseModels($components['schemas']);
        return iterator_to_array($models);
    }

    /*
     * delegate to model parser to get model instances
     */
    private function parseModels($schemas): \Generator
    {
        foreach ($schemas as $modelName => $modelSpec) {
            yield (new ModelParser($modelName, $modelSpec))->parse();
        }
    }
}
