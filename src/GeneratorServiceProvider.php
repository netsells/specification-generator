<?php

namespace Juddling\Parserator;

use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            Commands\SwaggerGenerateCommand::class,
        ]);
    }
}
