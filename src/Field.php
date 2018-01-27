<?php

namespace Juddling\OpenApiLaravel;

class Field
{
    private $name;
    private $type;
    private $required = false;

    public function __construct($name, DataType $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return DataType
     */
    public function getType()
    {
        return $this->type;
    }
}
