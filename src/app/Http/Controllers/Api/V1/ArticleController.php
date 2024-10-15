<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Обработчик просмотра статьи по id
     *
     * @param Request $request
     * @param int $article_id
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $article_id): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['message' => __('Запрос должен быть ajax')], 400);
        }

        $article = ArticleRepository::getArticleById(articleId: $article_id);
        if (!$article) {
            return response()->json(['message' => __('Статьи с ID \':id\' не существует', ['id' => $article_id])], 404);
        }

        return response()->json(['data' => $article->toArray()], 200);
    }
}
