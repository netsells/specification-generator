<?php

namespace Juddling\Parserator\Tests;

use Juddling\Parserator\Generators\MigrationGenerator;
use Juddling\Parserator\Parsers\OpenApiParser;
use Juddling\Parserator\Parsers\SwaggerParser;
use PHPUnit\Framework\TestCase;

class GenerateMigrationTest extends TestCase
{
    public function testOpenApiMigration()
    {
        $parser = new OpenApiParser(__DIR__ . '/openapi/spec.yaml');
        $models = $parser->models();
        $domainModel = $models[0];

        $generator = new MigrationGenerator($domainModel);
        $migration = $generator->generate();

        $this->assertContains('$table->string(\'name\')', $migration);
        $this->assertContains('Schema::create(\'domains\'', $migration);
    }

    public function testSwaggerMigration()
    {
        $parser = new SwaggerParser(__DIR__ . '/swagger/watchlotto.yaml');
        $models = $parser->models();

        foreach ($models as $model) {
            if ($model->getName() === 'Watch') {
                $generator = new MigrationGenerator($model);
                $migration = $generator->generate();

                $this->assertContains('$table->string(\'name\')', $migration);
                $this->assertContains('Schema::create(\'watches\'', $migration);
            }
        }
    }
}
