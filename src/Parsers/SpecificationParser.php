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
        $this->spec = $this->parseFile();
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

    /**
     * @return Model[]
     */
    public function migrationModels(): array
    {
        return array_filter($this->models(), function ($model) {
            return !$model->isSingleField() && !$model->isReferenced();
        });
    }

    /*
     * delegate to model parser to get model instances
     */
    protected function parseModels($schemas): iterable
    {
        $modelParser = $this->getModelParser();
        foreach ($schemas as $modelName => $modelSpec) {
            yield (new $modelParser($modelName, $modelSpec, []))->parse();
        }
    }

    private function parseFile()
    {
        // only exists in version 3.4+ of Symfony YAML
        if (method_exists(Yaml::class, 'parseFile')) {
            return Yaml::parseFile($this->file);
        }

        return Yaml::parse(file_get_contents($this->file));
    }
}
