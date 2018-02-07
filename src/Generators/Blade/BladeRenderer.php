<?php

namespace Juddling\Parserator\Generators\Blade;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class BladeRenderer
{
    public function compile($template, array $data)
    {
        $pathsToTemplates = [__DIR__ . '/../../../resources/views'];
        $pathToCompiledTemplates = __DIR__ . '/../../../resources/views/compiled';
        // Dependencies
        $filesystem = new Filesystem;
        $eventDispatcher = new Dispatcher(new Container);
        // Create View Factory capable of rendering PHP and Blade templates
        $viewResolver = new EngineResolver;
        $bladeCompiler = new BladeCompiler($filesystem, $pathToCompiledTemplates);
        $viewResolver->register('blade', function () use ($bladeCompiler, $filesystem) {
            return new CompilerEngine($bladeCompiler, $filesystem);
        });
        $viewResolver->register('php', function () {
            return new PhpEngine;
        });
        $viewFinder = new FileViewFinder($filesystem, $pathsToTemplates);
        $viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

        return $viewFactory->make($template, $data)->render();
    }
}
