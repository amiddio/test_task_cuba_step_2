<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Repositories\GeneralRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class SearchArticleController extends Controller
{

    /**
     * Обработчик поиска статей
     *
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(SearchRequest $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['message' => __('Запрос должен быть ajax')], 400);
        }

        $validated = $request->validated();
        try {
            $articles = GeneralRepository::searchArticlesByKeyWord(keyword: $validated['kw']);
            if (!$articles) {
                return response()->json(['message' => __('По вашему запросу ничего не найдено')], 404);
            }
            return response()->json(['data' => $articles], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
