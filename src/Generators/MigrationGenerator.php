<?php

namespace Juddling\Parserator\Generators;

use Juddling\Parserator\Model;
use Netsells\MigrationGenerator\MigrationBuilder;

class MigrationGenerator implements FileGenerator
{
    private $model;
    private $builder;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $tableName = $this->model->tableName();
        $migrationName = "create_{$tableName}_table";
        $this->builder = new MigrationBuilder(iterator_to_array($this->columns()), $migrationName, $tableName);
    }

    public function generate(): string
    {
        return $this->builder->generate();
    }

    public function fileName(): string
    {
        return $this->builder->fileName();
    }

    private function columns()
    {
        foreach ($this->model->getFields() as $field) {
            yield $field->toMigration();
        }
    }
}
