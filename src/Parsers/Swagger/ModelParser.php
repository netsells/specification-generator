<?php

namespace Juddling\Parserator\Parsers\Swagger;

class ModelParser extends \Juddling\Parserator\Parsers\ModelParser
{
    public static function models(array $definitions)
    {
        $evaluated = [];

        foreach ($definitions as $name => $specification) {
            if ($specification['type'] === 'object' && array_key_exists('properties', $specification)) {
                yield (new self($name, $specification))->parse();
            }
        }
    }
}