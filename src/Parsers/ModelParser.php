<?php

namespace Juddling\Parserator\Parsers;

use Juddling\Parserator\Exceptions\MissingReferenceException;
use Juddling\Parserator\Exceptions\UnparsedReferenceException;
use Juddling\Parserator\Model;

class ModelParser
{
    private $spec;
    private $name;
    /*
     * Definitions which have already been parsed, these are required as Definitions can reference
     * other Definitions
     */
    private $parsedDefinitions;
    /*
     * Parsed fields of the current Model
     */
    private $parsedFields = [];

    public function __construct($name, array $spec, $parsedDefinitions)
    {
        $this->spec = $spec;
        $this->name = $name;
        $this->parsedDefinitions = $parsedDefinitions;
    }

    public function parse()
    {
        // combine a reference and properties if allOf is used
        if (array_key_exists('allOf', $this->spec)) {
            return $this->allOfModel();
        }

        if (!array_key_exists('properties', $this->spec)) {
            return $this->singleField();
        }

        $fields = $this->parseFields($this->spec['properties']);
        return new Model($this->name, $fields);
    }

    private function parseFields($fields)
    {
        $parsedFields = [];
        foreach ($fields as $fieldName => $fieldSpec) {
            $parser = new FieldParser($fieldName, $fieldSpec, $this);
            $parsedFields[] = $parser->parse();
        }
        return $parsedFields;
    }

    private function allOfModel(): Model
    {
        $allOf = $this->spec['allOf'];

        // each is either a $ref, or properties
        foreach ($allOf as $complexFieldSpec) {
            if (array_key_exists('properties', $complexFieldSpec)) {
                $this->addFields($this->parseFields($complexFieldSpec['properties']));
                continue;
            }

            if (!array_key_exists('$ref', $complexFieldSpec)) {
                throw new MissingReferenceException("allOf has been used, but no reference has been provided in definition: {$this->name}");
            }

            $model = $this->getModelFromReference($complexFieldSpec['$ref']);
            $this->addFields($model->getFields());
        }

        return new Model($this->name, $this->parsedFields);
    }

    /*
     * Adds a set of fields to the current model's fields
     */
    private function addFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->parsedFields[] = $field;
        }
    }

    private function findParsedModel($referencedModelName): Model
    {
        foreach ($this->parsedDefinitions as $model) {
            if ($model->getName() === $referencedModelName) {
                return $model;
            }
        }

        throw new UnparsedReferenceException;
    }

    public function getModelFromReference($reference): Model
    {
        $referencedModelName = SwaggerParser::modelNameFromRef($reference);
        return $this->findParsedModel($referencedModelName);
    }

    /*
     * Definition is just a single field
     */
    private function singleField()
    {
        $parser = new FieldParser(null, $this->spec, $this);
        $model = new Model($this->name, [$parser->parse()]);
        $model->markAsSingleField();
        return $model;
    }
}
