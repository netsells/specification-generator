<?php

namespace Juddling\Parserator\Generators;

interface FileGenerator
{
    /*
     * Returns content of the file
     */
    public function generate(): string;

    public function fileName(): string;
}
