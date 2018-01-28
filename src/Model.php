<?php

namespace Juddling\Parserator;

class Model
{
    private $name;
    private $fields;
    private $singleField = false;
    private $referenced = false;

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

    /*
     * Returns true if this definition is not an object, but a single field
     * NOTE: this is not to check if an object just has a single field
     */
    public function isSingleField(): bool
    {
        return $this->singleField;
    }

    public function markAsSingleField()
    {
        $this->singleField = true;
    }

    public function markAsReferenced()
    {
        $this->referenced = true;
    }

    public function isReferenced(): bool
    {
        return $this->referenced;
    }
}
