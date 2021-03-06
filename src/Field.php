<?php

namespace Juddling\Parserator;

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

    public function getType(): DataType
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /*
     * A representation of the field which is passed to the migration generator
     */
    public function toMigration(): array
    {
        return [
            'name' => $this->getName(),
            'type' => $this->getType()->getValue(),
            'nullable' => false
        ];
    }
}
