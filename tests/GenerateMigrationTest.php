<?php

namespace Juddling\OpenApiLaravel\Tests;

use Juddling\OpenApiLaravel\Generators\MigrationGenerator;
use Juddling\OpenApiLaravel\OpenApiParser;
use PHPUnit\Framework\TestCase;

class GenerateMigrationTest extends TestCase
{
    public function testCreateDomainMigration()
    {
        $parser = new OpenApiParser(__DIR__ . '/openapi/spec.yaml');
        $models = $parser->models();
        $domainModel = $models[0];

        $generator = new MigrationGenerator($domainModel);
        $migration = $generator->generate();

        $this->assertContains('$table->string(\'name\')', $migration);
        $this->assertContains('Schema::create(\'domains\'', $migration);
    }
}