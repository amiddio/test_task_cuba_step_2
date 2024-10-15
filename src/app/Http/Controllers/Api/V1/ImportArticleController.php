<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\WikiArticleNotFound;
use App\Exceptions\WikiRequestIssue;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Parsers\Article;
use App\Parsers\SearchArticle;
use App\Repositories\GeneralRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Throwable;

class ImportArticleController extends Controller
{

    /**
     * Обработчик импорта статей с wikipedia в локальную БД
     *
     * @param ImportRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function __invoke(ImportRequest $request): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['message' => __('Запрос должен быть ajax')], 400);
        }

        $validated = $request->validated();

        try {
            $start = microtime(true);

            // Получаем информацию о статье
            $articleInfo = (new SearchArticle(inputData: $validated))->parse()->getData();

            Arr::set($articleInfo, 'language', $validated['language']);
            Arr::set($validated, 'page_id', $articleInfo['pageid']);

            // Запрашиваем статью в текстовом варианте (plain text)
            $articleContent = (new Article(inputData: $validated))->parse()->getData();

            // Импортируем статью
            $article = GeneralRepository::importArticle(info: $articleInfo, content: $articleContent);

            return response()->json([
                'data' => [
                    'title' => $article->title,
                    'url' => $article->url,
                    'size' => formatSize($article->size),
                    'total_words' => $article->total_words,
                    'time_execution' => sprintf('%.4f сек.', microtime(true) - $start),
                ],
            ], 201);
        } catch (WikiArticleNotFound $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (WikiRequestIssue $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => __('Внутренняя ошибка сервера')], 500);
        }
    }
}
