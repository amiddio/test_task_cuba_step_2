<?php

namespace App\Parsers;

use App\Exceptions\WikiArticleNotFound;
use App\Exceptions\WikiRequestIssue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SearchArticles extends BaseParser implements ParserInterface
{

    /**
     * @throws WikiRequestIssue
     */
    public function parse(): void
    {
        $response = Http::get($this->getUrl(), [
            'action' => 'query',
            'list' => 'search',
            'srsearch' => $this->inputData['title'],
            'format' => 'json',
        ]);

        if (!$response->successful()) {
            throw new WikiRequestIssue(__('An error occurred on the wiki'));
        }

        foreach ($response->json('query.search') as $item) {
            if (Str::lower($item['title']) == Str::lower($this->inputData['title'])) {
                $this->parsedData = $item;
                break;
            }
        }
    }

    /**
     * @throws WikiArticleNotFound
     */
    public function getArticle(): array
    {
        if (!$this->parsedData) {
            throw new WikiArticleNotFound(__('Article not found'));
        }

        return $this->parsedData;
    }
}
