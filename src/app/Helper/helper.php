<?php

if (!function_exists('formatSize'))
{
    /**
     * Функция переводит байты в килобайты, мегабайты и т.д.
     *
     * @param int $size
     * @return string
     */
    function formatSize(int $size): string
    {
        $metrics[0] = 'байт';
        $metrics[1] = 'Кб';
        $metrics[2] = 'Мб';
        $metrics[3] = 'Гб';
        $metrics[4] = 'Тб';
        $metric = 0;

        while (floor($size / 1024) > 0) {
            $metric++;
            $size /= 1024;
        }

        return round($size, 1) . " " . ($metrics[$metric] ?? '???');
    }
}

if (!function_exists('createAtomsFromPlainText'))
{
    /**
     * Функция разбивает текст на слова атомы,
     * и возвращает массив с кол-вом повторений
     *
     * @param string $text
     * @return array
     */
    function createAtomsFromPlainText(string $text): array
    {
        $text = mb_strtolower($text);

        $text = preg_replace('/\W+/u',' ', $text);
        $text = preg_replace('/\s+/u',' ', $text);
        $allWords = explode(' ', trim($text));

        $uniqueWords = array_count_values($allWords);

        $result = [];
        foreach ($uniqueWords as $atom => $count) {
            $result[] = ['atom' => strval($atom), 'cnt' => $count];
        }

        return [
            'atoms' => $result,
            'total_atoms' => array_sum($uniqueWords),
        ];
    }
}

if (!function_exists('articleCleaning'))
{
    /**
     * Удаляем лишние переводы строк у статьи
     *
     * @param string $text
     * @return string
     */
    function articleCleaning(string $text): string
    {
        return preg_replace('/\t+\n+/u','', $text);
    }
}
