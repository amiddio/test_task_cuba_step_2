<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Word;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

/**
 * Класс с методами содержащие бизнес-логику работы со статьями и атомами
 */
class GeneralRepository
{

    /**
     * @var string
     */
    private static string $wikiUrl = 'https://[LANG].wikipedia.org/wiki/[TITLE]';

    /**
     * Главный метод по сохранению статьи и ее атомов в БД
     *
     * @param array $info Мета информация статьи
     * @param array $content Полное содержание статьи
     * @return Model|null
     * @throws Throwable
     */
    public static function importArticle(array $info, array $content): ?Model
    {
        $article = null;

        DB::transaction(function () use ($info, $content, &$article) {
            $content = $content['extract'];

            // Получаем атомы
            $atomsInfo = createAtomsFromPlainText($content);

            // Сохраняем статью
            $article = Article::create([
                'url' => self::getArticleUrl(language: $info['language'], title: $info['title']),
                'title' => $info['title'],
                'size' => $info['size'],
                'total_words' => $atomsInfo['total_atoms'],
                'content' => $content,
            ]);

            $processedAtoms = [];
            foreach ($atomsInfo['atoms'] as $item) {
                $processedAtoms[] = ['atom' => $item['atom']];
            }

            // Сохраняем атомы
            self::insertAtoms($processedAtoms);

            // Создаем массив со связью между статьей и атомами
            $relationData = self::getArticleWordsRelationData(
                articleId: $article->id,
                atomsInfo: $atomsInfo,
                processedAtoms: $processedAtoms
            );

            // Сохраняем связи/отношения (статьи и ее атомов)
            self::insertArticleWordsRelationData($relationData);
        });

        return $article;
    }

    /**
     * Метод возвращает список статей
     *
     * @return Collection
     */
    public static function getArticlesList(): Collection
    {
        $column = ['id', 'title', 'url', 'size', 'total_words'];

        return Article::select($column)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Метод возвращает статью по ее id
     *
     * @param int $articleId
     * @return Model|null
     */
    public static function getArticleById(int $articleId): ?Model
    {
        return Article::find($articleId);
    }

    /**
     * Метод ищет статьи по атому
     *
     * @param string $keyword
     * @return Model|null
     */
    public static function searchArticlesByKeyWord(string $keyword): ?Model
    {
        return Word::with([
            'articles' => function ($query) {
                $query->select('title');
            },
        ])->where('atom', $keyword)->first();
    }

    /**
     * Метод получает атомы из локально БД, и формирует
     * необходимый массив для сохранения отношения со статьей
     *
     * @param int $articleId
     * @param array $atomsInfo
     * @param array $processedAtoms
     * @return array
     */
    private static function getArticleWordsRelationData(int $articleId, array $atomsInfo, array $processedAtoms): array
    {
        $result = [];
        $processedAtoms = Arr::pluck($processedAtoms, 'atom');

        $atomsInDb = DB::table('words')->whereIn('atom', $processedAtoms)->get();
        foreach ($atomsInDb as $atom) {
            $cnt = 0;
            foreach ($atomsInfo['atoms'] as $item) {
                if ($atom->atom == $item['atom']) {
                    $cnt = $item['cnt'];
                    break;
                }
            }
            $result[] = [
                'article_id' => $articleId,
                'word_id' => $atom->id,
                'cnt' => $cnt,
            ];
        }

        return $result;
    }

    /**
     * Метод вставляет массив атомов в БД
     *
     * @param array $rows
     * @return void
     */
    private static function insertAtoms(array $rows): void
    {
        DB::table('words')->insertOrIgnore($rows);
    }

    /**
     * Метод сохраняет отношения статьи и атомов в БД
     *
     * @param array $rows
     * @return void
     */
    private static function insertArticleWordsRelationData(array $rows): void
    {
        DB::table('article_word')->insert($rows);
    }

    /**
     * Метод на основе языка и названия статьи возвращает url на wikipedia сайт
     *
     * @param string $language
     * @param string $title
     * @return string
     */
    private static function getArticleUrl(string $language, string $title): string
    {
        return Str::of(self::$wikiUrl)
            ->replace('[LANG]', $language)
            ->replace('[TITLE]', $title);
    }
}
