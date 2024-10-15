<?php

namespace App\Parsers;

use App\Exceptions\WikiArticleNotFound;
use App\Exceptions\WikiRequestIssue;
use Illuminate\Support\Facades\Http;

/**
 * Парсер детальной информации статьи
 */
class Article extends BaseParser implements ParserInterface
{

    /**
     * Метод запускающий парсинг.
     * По названию статьи получаем ее полное содержание.
     * Сам контент приходит как текст (plain text)
     *
     * @return object
     * @throws WikiRequestIssue
     * @throws WikiArticleNotFound
     */
    public function parse(): object
    {
        $response = Http::get($this->getUrl(), [
            'action' => 'query',
            'prop' => 'extracts',
            'format' => 'json',
            'titles' => $this->inputData['title'],
            'explaintext' => 1,
        ]);

        if (!$response->successful()) {
            throw new WikiRequestIssue(__('Произошла ошибка на wikipedia ресурсе'));
        }

        foreach ($response->json('query.pages') as $pageId => $article) {
            if ($pageId <= 0) {
                throw new WikiArticleNotFound(__('Статья не найдена на wikipedia ресурсе'));
            }
            if ($article['pageid'] == $this->inputData['page_id']) {
                $this->parsedData = $article;
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
