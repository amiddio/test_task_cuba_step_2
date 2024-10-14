<?php

namespace App\Parsers;

use Illuminate\Support\Str;

abstract class BaseParser
{
    protected array $parsedData = [];

    /**
     * @var string
     */
    private string $url = 'https://[LANG].wikipedia.org/w/api.php';

    /**
     * @param array $inputData
     */
    public function __construct(
        protected readonly array $inputData
    ) {
    }

    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return Str::replace('[LANG]', $this->inputData['language'], $this->url);
    }
}
