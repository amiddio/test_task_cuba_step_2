<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Parsers\Article;
use App\Parsers\SearchArticles;
use Exception;
use Illuminate\Support\Arr;

class ImportArticleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ImportRequest $request)
    {
        if (!$request->ajax()) {
            return response(['message' => __('Request must be ajax')], 400);
        }

        $validated = $request->validated();

        try {
            $searchArticles = new SearchArticles(inputData: $validated);
            $searchArticles->parse();
            $articleInfo = $searchArticles->getArticle();

            Arr::set($validated, 'page_id', $articleInfo['pageid']);
            $article = new Article(inputData: $validated);
            $article->parse();
            $articlePlainText = $article->getPlainText();
            $articleParsedWords = $article->getParsedWords();
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], 404);
        }

        return response(['status' => 'ok'], 200);
    }
}
