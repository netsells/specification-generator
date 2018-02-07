<?php

namespace Juddling\Parserator\Generators;

use Juddling\Parserator\Generators\Blade\BladeRenderer;
use Juddling\Parserator\Model;

class BladeFormGenerator implements FileGenerator
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function generate(): string
    {
        return (new BladeRenderer())->compile('form', ['fields' => $this->model->getFields()]);
    }

    public function fileName(): string
    {
        return snake_case($this->model->getName()) . '.blade.php';
    }
}
