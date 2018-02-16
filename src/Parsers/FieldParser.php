<?php

namespace Juddling\Parserator\Parsers;

use Juddling\Parserator\DataType;
use Juddling\Parserator\Exceptions\ParsingException;
use Juddling\Parserator\Exceptions\UnsupportedTypeException;
use Juddling\Parserator\Field;
use Juddling\Parserator\ModelField;

/*
 * Parses data types for a the fields on a model
 */

class FieldParser
{
    private $spec;
    private $name;
    private $modelParser;
    private $referencedModel;

    public function __construct($name, array $spec, ModelParser $modelParser)
    {
        $this->spec = $spec;
        $this->name = $name;
        $this->modelParser = $modelParser;
    }

    public function parse(): Field
    {
        if ($this->isModelField()) {
            return new ModelField($this->name, $this->referencedModel);
        }

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
                throw new \RuntimeException("Can't get the type if there is more than a single field, field named: " .
                    "{$this->name} is referencing model: {$model->getName()}");
            }

            return $model->getFields()[0]->getType();
        }

        if (!array_key_exists('type', $this->spec)) {
            throw new ParsingException("No type property defined on Field: {$this->name}");
        }

        try {
            return new DataType($this->spec['type']);
        } catch (\UnexpectedValueException $e) {
            throw new UnsupportedTypeException($e->getMessage());
        }
    }

    /*
     * This is not a good way of testing for a model field,
     * better way is to make sure we are not parsing from within an allOf
     */
    private function isModelField(): bool
    {
        if (!$this->isReference()) {
            return false;
        }

        $this->referencedModel = $this->modelParser->getModelFromReference($this->spec['$ref']);
        return count($this->referencedModel->getFields()) > 1;
    }
}
