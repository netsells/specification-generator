<?php

namespace Juddling\Parserator;

class Model
{
    private $name;
    private $fields;

    public function __construct($name, $fields)
    {
        $this->name = $name;
        $this->fields = $fields instanceof \Traversable ? iterator_to_array($fields) : $fields;
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
