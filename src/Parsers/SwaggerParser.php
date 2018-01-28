<?php

namespace Juddling\Parserator\Parsers;

class SwaggerParser extends SpecificationParser
{
    public static function modelNameFromRef($reference): string
    {
        $startingIndex = strlen('#/definitions/');
        return substr($reference, $startingIndex);
    }

    protected function getModelsFromSpecification(): array
    {
        return $this->spec['definitions'];
    }

    protected function getModelParser()
    {
        return Swagger\ModelParser::class;
    }

    /*
     * delegate to model parser to get model instances
     */
    protected function parseModels($schemas): iterable
    {
        $modelParser = $this->getModelParser();
        return $modelParser::models($schemas);
    }
}
