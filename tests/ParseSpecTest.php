<?php

namespace Juddling\OpenApiLaravel\Tests;

use Juddling\OpenApiLaravel\OpenApiParser;
use PHPUnit\Framework\TestCase;

class ParseSpecTest extends TestCase
{
    public function testReadSpecTest()
    {
        $file = __DIR__ . '/openapi/spec.yaml';
        $parser = new OpenApiParser($file);
        $this->assertCount(2, $parser->models());
    }
}