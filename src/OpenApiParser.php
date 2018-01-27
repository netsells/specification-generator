<?php

namespace Juddling\OpenApiLaravel;

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

    public function models()
    {
        $components = $this->spec['components'];

        if (!array_key_exists('schemas', $components)) {
            throw new \RuntimeException("No models found in components.schemas");
        }

        return $this->parseModels($components['schemas']);
    }

    /*
     * delegate to model parser to get model instances
     */
    private function parseModels($schemas)
    {
        foreach ($schemas as $modelName => $modelSpec) {
            yield (new ModelParser($modelName, $modelSpec))->parse();
        }
    }
}
