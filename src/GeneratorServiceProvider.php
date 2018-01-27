<?php

namespace Juddling\OpenApiLaravel;

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
