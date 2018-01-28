<?php

namespace Juddling\Parserator\Parsers;

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
        $fields = $this->parseFields($this->spec['properties']);
        return new Model($this->name, $fields);
    }

    private function parseFields($fields)
    {
        foreach ($fields as $fieldName => $fieldSpec) {
            $parser = new FieldParser($fieldName, $fieldSpec);

            if (!array_key_exists('type', $fieldSpec)) {
                continue;
            }

            yield $parser->parse();
        }
    }
}
