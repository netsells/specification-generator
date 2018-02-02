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

    public function toMigration(): array
    {
        return [
            'name' => $this->getName() . '_id',
            'type' => 'integer',
            'nullable' => true,
            'unsigned' => true,
            'is_foreign_key' => true,
            'foreign_key' => [
                'references' => $this->model->tableName(),
                'on_update' => 'restrict',
                'on_delete' => 'restrict',
            ]
        ];
    }
}
