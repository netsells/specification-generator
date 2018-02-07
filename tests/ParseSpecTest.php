<?php

namespace Juddling\Parserator\Tests;

use Juddling\Parserator\DataType;
use Juddling\Parserator\Field;
use Juddling\Parserator\Model;
use Juddling\Parserator\Parsers\OpenApiParser;
use Juddling\Parserator\Parsers\Swagger\ModelParser;
use Juddling\Parserator\Parsers\SwaggerParser;
use League\BooBoo\Formatter\CommandLineFormatter;
use PHPUnit\Framework\TestCase;

class ParseSpecTest extends TestCase
{
    public function testInvalidDataType()
    {
        $this->expectException(\UnexpectedValueException::class);
        new DataType('some-invalid-type');
    }

    public function testSwaggerSpec()
    {
        $parser = new SwaggerParser(__DIR__ . '/swagger/watchlotto.yaml');
        $models = $parser->migrationModels();
        $this->assertContainsOnlyInstancesOf(Model::class, $models);
        // should really be five models, however looking at the spec, there is no way for me
        // to determine that RegistrationUser, shouldn't be a database table
        // Game,Watch,User,RegistrationUser,NotificationSetting,Card
        $this->assertCount(6, $models);

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
        $this->assertSame('Name', SwaggerParser::modelNameFromRef('#/definitions/Name'));
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

    public function testSwaggerComplexSpec()
    {
        $parser = new SwaggerParser(__DIR__ . '/swagger/watchlotto-2.yaml');
        $models = $parser->models();
        $this->assertContainsOnlyInstancesOf(Model::class, $models);

        foreach ($models as $model) {
            $this->assertContainsOnlyInstancesOf(Field::class, $model->getFields());

            switch ($model->getName()) {
                case 'Play':
                    $fields = $model->getFields();
                    $this->assertFieldsContainsField(new Field('spot_x', DataType::INTEGER()), $fields);
                    $this->assertFieldsContainsField(new Field('spot_y', DataType::INTEGER()), $fields);
                    break;
                case 'BasicUser':
                    $fields = $model->getFields();
                    $this->assertCount(5, $fields);
                    break;
                case 'Name':
                    $fields = $model->getFields();
                    $this->assertCount(2, $fields);
                    break;
                case 'User':
                    $fields = $model->getFields();
                    $this->assertCount(6, $fields);
                    break;
            }
        }
    }
}
