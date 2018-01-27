<?php

namespace Juddling\OpenApiLaravel\Tests;

use Juddling\OpenApiLaravel\DataType;
use Juddling\OpenApiLaravel\Parsers\OpenApiParser;
use PHPUnit\Framework\TestCase;

class ParseSpecTest extends TestCase
{
    public function testReadSpecTest()
    {
        $file = __DIR__ . '/openapi/spec.yaml';
        $parser = new OpenApiParser($file);
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
}
