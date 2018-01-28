<?php

namespace Juddling\Parserator\Parsers;

use Juddling\Parserator\Exceptions\MissingReferenceException;
use Juddling\Parserator\Model;

class ModelParser
{
    private $spec;
    private $name;

    public function __construct($name, array $spec)
    {
        $this->spec = $spec;
        $this->name = $name;
    }

    public function parse()
    {
        // combine a reference and properties if allOf is used
        if (array_key_exists('allOf', $this->spec)) {
            return $this->allOfModel();
        }

        $fields = $this->parseFields($this->spec['properties']);
        return new Model($this->name, $fields);
    }

    public static function modelNameFromRef($reference): string
    {
        $startingIndex = strlen('#/definitions/');
        return substr($reference, $startingIndex);
    }

    private function parseFields($fields)
    {
        foreach ($fields as $fieldName => $fieldSpec) {
            $parser = new FieldParser($fieldName, $fieldSpec);
            yield $parser->parse();
        }
    }

    private function allOfModel(): Model
    {
        $allOf = $this->spec['allOf'];
        $fields = [];

        // each is either a $ref, or properties
        foreach ($allOf as $complexFieldSpec) {
            if (array_key_exists('properties', $complexFieldSpec)) {
                $parsedFields = $this->parseFields($complexFieldSpec['properties']);
                return new Model($this->name, $parsedFields);
            }

            if (!array_key_exists('$ref', $complexFieldSpec)) {
                throw new MissingReferenceException("allOf has been used, but no reference has been provided in definition: {$this->name}");
            }

            $referencedModelName = self::modelNameFromRef($complexFieldSpec['$ref']);
        }

        return new Model($this->name, $fields);
    }
}
