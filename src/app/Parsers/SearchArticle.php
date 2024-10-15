<?php

namespace App\Parsers;

use App\Exceptions\WikiArticleNotFound;
use App\Exceptions\WikiRequestIssue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SearchArticle extends BaseParser implements ParserInterface
{

    /**
     * Метод запускающий парсинг.
     * По названии статьи получаем мета информацию.
     *
     * @return object
     * @throws WikiRequestIssue
     */
    public function parse(): object
    {
        $response = Http::get($this->getUrl(), [
            'action' => 'query',
            'list' => 'search',
            'srsearch' => $this->inputData['title'],
            'format' => 'json',
        ]);

        if (!$response->successful()) {
            throw new WikiRequestIssue(__('Произошла ошибка на wikipedia ресурсе'));
        }

        foreach ($response->json('query.search') as $item) {
            if (Str::lower($item['title']) == Str::lower($this->inputData['title'])) {
                $this->parsedData = $item;
                break;
            }
        }

        return $this;
    }

    /**
     * Метод возвращает полученные после парсинга данные
     *
     * @return array
     * @throws WikiArticleNotFound
     */
    public function getData(): array
    {
        if (!$this->parsedData) {
            throw new WikiArticleNotFound(__('Статья не найдена на wikipedia ресурсе'));
        }

        return $this->parsedData;
    }

}
