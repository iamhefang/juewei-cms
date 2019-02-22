<?php
defined('PROJECT_NAME') or die("Access Refused");

function icp(string $icp = null)
{
    return $icp ? "<a href='http://www.miitbeian.gov.cn/' target='_blank'>{$icp}</a>" : '';
}

function article($article): \link\hefang\site\content\models\ViewArticleModel
{
    return $article;
}

function pager($pager): \link\hefang\mvc\entities\Pager
{
    return $pager;
}

function config($name, $defaultValue = null)
{
    $theme = \link\hefang\mvc\Mvc::getConfig('system|theme', link\hefang\mvc\Mvc::getDefaultTheme());
    return link\hefang\mvc\Mvc::getConfig("theme_settings_{$theme}|{$name}", $defaultValue);
}
//function article(string $idOrAlias = null): \link\hefang\site\content\models\ViewArticleModel
//{
//    try {
//        $m = \link\hefang\mvc\Mvc::getCache()->get('article' . $idOrAlias);
//        if (\link\hefang\mvc\Mvc::isDebug() || !($m instanceof \link\hefang\site\content\models\ViewArticleModel)) {
//            $m = \link\hefang\site\content\models\ViewArticleModel::find("`id` = '{$idOrAlias}' OR `alias` = '{$idOrAlias}'");
//        }
//        link\hefang\mvc\Mvc::getCache()->set('article' . $idOrAlias, $m);
//        return $m;
//    } catch (Throwable $e) {
//        \link\hefang\mvc\Mvc::getLogger()->error("文章详情异常", $e->getMessage(), $e);
//        return new \link\hefang\site\content\models\ViewArticleModel();
//    }
//}
//
//function articlePager(int $pageIndex, int $pageSize = null, string $search = null, \link\hefang\mvc\databases\SqlSort $sort = null, string $where = null): \link\hefang\mvc\entities\Pager
//{
//    $pageSize === null and $pageSize = \link\hefang\mvc\Mvc::getDefaultPageSize();
//    $order = [$sort];
//    try {
//        return \link\hefang\site\content\models\ViewArticleModel::pager(
//            $pageIndex, $pageSize, $search, $where, $order
//        );
//    } catch (Throwable $e) {
//        \link\hefang\mvc\Mvc::getLogger()->error("文章列表异常", $e->getMessage(), $e);
//        return new \link\hefang\mvc\entities\Pager(0, $pageIndex, $pageSize, []);
//    }
//}
