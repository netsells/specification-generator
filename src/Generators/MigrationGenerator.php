<?php

namespace Juddling\OpenApiLaravel\Generators;

use Juddling\OpenApiLaravel\Model;
use Netsells\MigrationGenerator\MigrationBuilder;

class MigrationGenerator implements FileGenerator
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function generate()
    {
        $tableName = $this->tableName();
        $migrationName = 'create_{$tableName}_table';
        $builder = new MigrationBuilder(iterator_to_array($this->columns()), $migrationName, $tableName);
        return $builder->generate();
    }

    private function tableName()
    {
        return snake_case(str_plural($this->model->getName()));
    }

    private function columns()
    {
        foreach ($this->model->getFields() as $field) {
            yield [
                'name' => $field->getName(),
                'type' => $field->getType()->getValue(),
                'nullable' => false
            ];
        }
    }
}
