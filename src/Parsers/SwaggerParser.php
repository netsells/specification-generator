<?php

namespace Juddling\Parserator\Parsers;

class SwaggerParser extends SpecificationParser
{
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
    protected function parseModels($schemas): \Generator
    {
        $modelParser = $this->getModelParser();
        return $modelParser::models($schemas);
    }
}
