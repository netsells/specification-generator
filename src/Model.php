<?php

namespace Juddling\Parserator;

class Model
{
    private $name;
    private $fields;

    public function __construct($name, $fields)
    {
        $this->name = $name;
        $this->fields = $fields;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
