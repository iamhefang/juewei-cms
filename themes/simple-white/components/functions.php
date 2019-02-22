<?php

function articleDate(\link\hefang\site\content\models\ViewArticleModel $model): string
{
    $year = +date('Y', $model->getPostTime());
    $month = date('m', $model->getPostTime());
    $day = date('d', $model->getPostTime());
    $urlPrefix = link\hefang\mvc\Mvc::getUrlPrefix();
    return "<a href='{$urlPrefix}/{$year}/'>{$year}</a>年<a href='{$urlPrefix}/{$year}/{$month}/'>{$month}</a>月<a href='{$urlPrefix}/{$year}/{$month}/{$day}/'>{$day}</a>日";
}

function highlight(string $html, string $lightWords = null): string
{
    if (\link\hefang\helpers\StringHelper::isNullOrBlank($lightWords)) return $html;
    return preg_replace_callback('#(' . $lightWords . '){1}#is', function (array $match) {
        return "<span style='color: red'>{$match[1]}</span>";
    }, $html);
}