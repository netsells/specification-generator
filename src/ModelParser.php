<?php

namespace Juddling\OpenApiLaravel;

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
        return new Model($this->name, $this->spec['properties']);
    }
}
