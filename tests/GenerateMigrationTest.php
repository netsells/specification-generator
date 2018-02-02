<?php

namespace Juddling\Parserator\Tests;

use Juddling\Parserator\Generators\MigrationGenerator;
use Juddling\Parserator\Parsers\OpenApiParser;
use Juddling\Parserator\Parsers\SwaggerParser;
use PHPUnit\Framework\TestCase;

class GenerateMigrationTest extends TestCase
{
    public function testSwaggerMigration()
    {
        $parser = new SwaggerParser(__DIR__ . '/swagger/watchlotto.yaml');
        $models = $parser->models();

        foreach ($models as $model) {
            switch ($model->getName()) {
                case 'Watch':
                    $generator = new MigrationGenerator($model);
                    $migration = $generator->generate();

                    $this->assertContains('$table->string(\'name\')', $migration);
                    $this->assertContains('Schema::create(\'watches\'', $migration);

                    break;
                case 'User':
                    $generator = new MigrationGenerator($model);
                    $migration = $generator->generate();

                    $this->assertContains('Schema::create(\'users\'', $migration);

                    $this->assertContains('$table->string(\'first_name\')', $migration);
                    $this->assertContains('$table->string(\'last_name\')', $migration);
                    $this->assertContains('$table->string(\'email\')', $migration);
                    $this->assertContains('$table->string(\'phone\')', $migration);
                    $this->assertContains('$table->boolean(\'marketing\')', $migration);
                    $this->assertContains('$table->string(\'profile_image\')', $migration);

                    break;
            }
        }
    }
}
