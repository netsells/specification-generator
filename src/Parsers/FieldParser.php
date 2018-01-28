<?php

namespace Juddling\Parserator\Parsers;

use Juddling\Parserator\DataType;
use Juddling\Parserator\Field;

/*
 * Parses data types for a the fields on a model
 */
class FieldParser
{
    private $spec;
    private $name;
    private $modelParser;

    public function __construct($name, array $spec, ModelParser $modelParser)
    {
        $this->spec = $spec;
        $this->name = $name;
        $this->modelParser = $modelParser;
    }

    public function parse(): Field
    {
        return new Field($this->name, $this->getType());
    }

    /*
     * Property may not have a type, but may instead refer to another Model for its specification
     */
    private function isReference()
    {
        return array_key_exists('$ref', $this->spec) && !array_key_exists('type', $this->spec);
    }

    private function getType(): DataType
    {
        if ($this->isReference()) {
            $model = $this->modelParser->getModelFromReference($this->spec['$ref']);

            if (count($model->getFields()) > 1) {
                throw new \RuntimeException("Can't get the type if there is more than a single field");
            }

            return $model->getFields()[0]->getType();
        }

        return new DataType($this->spec['type']);
    }
}
