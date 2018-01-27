<?php

namespace Juddling\OpenApiLaravel\Tests;

use Juddling\OpenApiLaravel\DataType;
use Juddling\OpenApiLaravel\Parsers\OpenApiParser;
use Juddling\OpenApiLaravel\Parsers\SwaggerParser;
use PHPUnit\Framework\TestCase;

class ParseSpecTest extends TestCase
{
    public function testOpenApiSpec()
    {
        $parser = new OpenApiParser(__DIR__ . '/openapi/spec.yaml');
        $models = $parser->models();
        $this->assertCount(2, $models);

        $firstModel = $models[0];
        $this->assertSame('Domain', $firstModel->getName());
        $this->assertCount(2, $firstModel->getFields());

        $secondModel = $models[1];
        $this->assertSame('SupplierDomain', $secondModel->getName());
        $this->assertCount(2, $secondModel->getFields());
    }

    public function testInvalidDataType()
    {
        $this->expectException(\UnexpectedValueException::class);
        new DataType('some-invalid-type');
    }

    public function testSwaggerSpec()
    {
        $parser = new SwaggerParser(__DIR__ . '/swagger/watchlotto.yaml');
        $models = $parser->models();
        $this->assertCount(5, $models);
    }
}
