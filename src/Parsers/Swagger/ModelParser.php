<?php

namespace Juddling\Parserator\Parsers\Swagger;

use Juddling\Parserator\Exceptions\UnparsedReferenceException;
use Juddling\Parserator\Queue;

class ModelParser extends \Juddling\Parserator\Parsers\ModelParser
{
    public static function models(array $definitions)
    {
        $evaluated = [];
        // queue doesn't handle dictionaries, so just use keys
        $queue = new Queue(array_keys($definitions));

        while ($queue->length() > 0) {
            $name = $queue->pop();
            $specification = $definitions[$name];

            if ($specification['type'] === 'object') {
                try {
                    $model = (new self($name, $specification))->parse();
                    $evaluated[] = $model;
                } catch (UnparsedReferenceException $e) {
                    // this model references another model we haven't parsed yet
                    // add it back on the queue and try again later!
                    $queue->push($name);
                }
            }
        }

        return $evaluated;
    }
}