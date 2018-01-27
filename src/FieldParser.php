<?php

namespace Juddling\OpenApiLaravel;

/*
 * Parses data types for a the fields on a model
 */
class FieldParser
{
    private $spec;
    private $name;

    public function __construct($name, array $spec)
    {
        $this->spec = $spec;
        $this->name = $name;
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
            return new DataType(DataType::REFERENCE);
        }

        return new DataType($this->spec['type']);
    }
}
