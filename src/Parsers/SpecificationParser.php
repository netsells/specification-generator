<?php

namespace Juddling\Parserator\Parsers;

use Juddling\Parserator\Model;
use Symfony\Component\Yaml\Yaml;

abstract class SpecificationParser
{
    protected $file;
    protected $spec;

    abstract protected function getModelsFromSpecification(): array;
    abstract protected function getModelParser();

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

        if ($models instanceof \Traversable) {
            return iterator_to_array($models);
        }

        return $models;
    }

    /*
     * delegate to model parser to get model instances
     */
    protected function parseModels($schemas): iterable
    {
        $modelParser = $this->getModelParser();
        foreach ($schemas as $modelName => $modelSpec) {
            yield (new $modelParser($modelName, $modelSpec))->parse();
        }
    }
}
