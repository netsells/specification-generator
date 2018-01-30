<?php

namespace Juddling\Parserator;

class ModelField extends Field
{
    private $model;

    public function __construct($name, Model $model)
    {
        parent::__construct($name, DataType::REFERENCE());

        $this->model = $model;
    }
}
