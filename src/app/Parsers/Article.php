<?php

namespace App\Parsers;

use App\Exceptions\WikiArticleNotFound;
use App\Exceptions\WikiRequestIssue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Article extends BaseParser implements ParserInterface
{

    /**
     * @return object
     * @throws WikiRequestIssue
     * @throws WikiArticleNotFound
     */
    public function parse(): void
    {
        $response = Http::get($this->getUrl(), [
            'action' => 'query',
            'prop' => 'extracts',
            'format' => 'json',
            'titles' => $this->inputData['title'],
            'explaintext' => 1,
        ]);

        if (!$response->successful()) {
            throw new WikiRequestIssue(__('An error occurred on the wiki'));
        }

        foreach ($response->json('query.pages') as $pageId => $article) {
            if ($pageId <= 0) {
                throw new WikiArticleNotFound(__('Article not found'));
            }
            if ($article['pageid'] == $this->inputData['page_id']) {
                $this->parsedData = $article;
                break;
            }
        }
    }

    /**
     * @return ?string
     * @throws WikiArticleNotFound
     */
    public function getPlainText(): ?string
    {
        if (isset($this->parsedData['extract'])) {
            return $this->parsedData['extract'];
        }

        throw new WikiArticleNotFound(__('Article not found'));
    }

    /**
     * @return array|null
     */
    public function getParsedWords(): ?array
    {
        if (isset($this->parsedData['extract'])) {
            $article = Str::lower($this->parsedData['extract']);

            preg_match_all("/(\w+)/ui", $article, $matches);
            if (!isset($matches[0])) {
                return null;
            }
            $uniqueWords = array_count_values($matches[0]);

            return [
                'all_unique_words' => $uniqueWords,
                'total_words' => array_sum($uniqueWords),
            ];
        }

        return null;
    }
}
