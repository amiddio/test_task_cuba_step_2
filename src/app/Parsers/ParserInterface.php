<?php

namespace App\Parsers;

interface ParserInterface
{
    /**
     * @return object
     */
    public function parse(): object;

    /**
     * @return array
     */
    public function getData(): array;
}
