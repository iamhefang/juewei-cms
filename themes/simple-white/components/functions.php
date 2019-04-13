<?php

function articleDate(\link\hefang\site\content\models\ViewArticleModel $model): string
{
    $year = +date('Y', $model->getPostTime());
    $month = date('m', $model->getPostTime());
    $day = date('d', $model->getPostTime());
    $urlPrefix = link\hefang\mvc\Mvc::getUrlPrefix();
    return "<a href='{$urlPrefix}/{$year}/'>{$year}</a>年<a href='{$urlPrefix}/{$year}/{$month}/'>{$month}</a>月<a href='{$urlPrefix}/{$year}/{$month}/{$day}/'>{$day}</a>日";
}

function highlight($html, string $lightWords = null): string
{
    if ($html === null) return '';
    if (\link\hefang\helpers\StringHelper::isNullOrBlank($lightWords)) return $html;
    return preg_replace_callback('#(' . $lightWords . '){1}#is', function (array $match) {
        return "<span style='color: red'>{$match[1]}</span>";
    }, $html);
}

function baiduJsonLD(\link\hefang\site\content\models\ViewArticleModel $article)
{
    $alias = $article->getAlias() ?: $article->getId();
    $json = json_encode([
        '@context' => 'https://ziyuan.baidu.com/contexts/cambrian.jsonld',
        '@id' => "https://hefang.link/article/{$alias}.html",
        'appid' => '1603685781640801',
        'title' => $article->getTitle(),
        'images' => array_map(function (string $cover) {
            return 'https://hefang.link' . $cover;
        }, $article->getCovers()),
        'pubDate' => date('Y-m-d', $article->getPostTime()) . 'T' . date('H:i:s', $article->getPostTime())
    ], JSON_UNESCAPED_UNICODE);
    return str_replace('\/', '/', $json);
}

function topArticle(string $type, int $pageSize = 5): array
{
    try {
        $pager = \link\hefang\site\content\models\ArticleModel::pager(
            1,
            $pageSize,
            null,
            "`is_draft` = false AND `enable` = true AND `type` = 'article'",
            [new \link\hefang\mvc\databases\SqlSort($type === 'new' ? "post_time" : "read_count", \link\hefang\mvc\databases\SqlSort::TYPE_DESC)]
        );
        return $pager->getData();
    } catch (Throwable $e) {
        \link\hefang\mvc\Mvc::getLogger()->error("最新文章列表异常", $e->getMessage(), $e);
        return [];
    }
}