<?php

namespace Juddling\Parserator\Parsers;

class OpenApiParser extends SpecificationParser
{
    protected function getModelsFromSpecification(): array
    {
        $components = $this->spec['components'];

        if (!array_key_exists('schemas', $components)) {
            throw new \RuntimeException("No models found in components.schemas");
        }

        return $components['schemas'];
    }

    protected function getModelParser()
    {
        return ModelParser::class;
    }
}
