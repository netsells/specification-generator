<?php

namespace Juddling\OpenApiLaravel;

class Model
{
    private $name;
    private $fields;

    public function __construct($name, $fields)
    {
        $this->name = $name;
        $this->fields = $fields;
    }
}
