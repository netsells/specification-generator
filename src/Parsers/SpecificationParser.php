<?php

namespace Juddling\OpenApiLaravel\Parsers;

use Juddling\OpenApiLaravel\Model;
use Symfony\Component\Yaml\Yaml;

abstract class SpecificationParser
{
    protected $file;
    protected $spec;

    abstract protected function getModelsFromSpecification(): array;

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
        $models = $this->parseModels($this->getModelsFromSpecification());
        return iterator_to_array($models);
    }

    /*
     * delegate to model parser to get model instances
     */
    protected function parseModels($schemas): \Generator
    {
        foreach ($schemas as $modelName => $modelSpec) {
            yield (new ModelParser($modelName, $modelSpec))->parse();
        }
    }
}
