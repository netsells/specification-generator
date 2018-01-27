<?php

namespace Juddling\OpenApiLaravel;

use MyCLabs\Enum\Enum;

class DataType extends Enum
{
    // OpenAPI data types: https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.1.md#data-types
    const INTEGER = 'integer';
    const LONG = 'long';
    const FLOAT = 'float';
    const DOUBLE = 'double';
    const STRING = 'string';
    const BYTE = 'byte';
    const BINARY = 'binary';
    const BOOLEAN = 'boolean';
    const DATE = 'date';
    const DATETIME = 'dateTime';
    const PASSWORD = 'password';

    const REFERENCE = '$ref';
}
