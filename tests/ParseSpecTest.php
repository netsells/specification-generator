<?php

namespace Juddling\Parserator\Tests;

use Juddling\Parserator\DataType;
use Juddling\Parserator\Field;
use Juddling\Parserator\Model;
use Juddling\Parserator\Parsers\OpenApiParser;
use Juddling\Parserator\Parsers\Swagger\ModelParser;
use Juddling\Parserator\Parsers\SwaggerParser;
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
        $this->assertContainsOnlyInstancesOf(Model::class, $models);
        $this->assertCount(8, $models);

        foreach ($models as $model) {
            $this->assertContainsOnlyInstancesOf(Field::class, $model->getFields());

            switch ($model->getName()) {
                case 'User':
                    $fields = $model->getFields();
                    $this->assertFieldsContainsField(new Field('first_name', DataType::STRING()), $fields);
                    $this->assertFieldsContainsField(new Field('last_name', DataType::STRING()), $fields);
                    $this->assertFieldsContainsField(new Field('email', DataType::STRING()), $fields);
                    $this->assertFieldsContainsField(new Field('phone', DataType::STRING()), $fields);
                    $this->assertFieldsContainsField(new Field('marketing', DataType::BOOLEAN()), $fields);
                    $this->assertFieldsContainsField(new Field('profile_image', DataType::STRING()), $fields);
                    break;
                case 'Game':
                    $fields = $model->getFields();
                    $this->assertFieldsContainsField(new Field('id', DataType::STRING()), $fields);
                    $this->assertFieldsContainsField(new Field('name', DataType::STRING()), $fields);
                    $this->assertFieldsContainsField(new Field('starts_at', DataType::STRING()), $fields);
                    $this->assertFieldsContainsField(new Field('ends_at', DataType::STRING()), $fields);
                    break;
            }
        }
    }

    public function testModelNameFromReference()
    {
        $this->assertSame('Name', ModelParser::modelNameFromRef('#/definitions/Name'));
    }

    private function assertFieldsContainsField(Field $x, $fields)
    {
        foreach ($fields as $y) {
            if ($x->getName() === $y->getName()) {
                if ($x->getType() == $y->getType()) {
                    return;
                }

                $this->fail("Type mismatch for field name: {$x->getName()} - expected: {$x->getType()} actual: {$y->getType()}");
            }
        }

        $this->fail('Could not find field with name: ' . $x->getName() . ' and type: ' . $x->getType());
    }
}
