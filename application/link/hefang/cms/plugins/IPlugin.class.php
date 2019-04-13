<?php

namespace link\hefang\cms\plugins;
defined('PHP_MVC') or die('Access Refused');


use link\hefang\cms\plugins\handlers\TemplateHandler;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\entities\Router;
use link\hefang\site\content\models\ArticleModel;

interface IPlugin
{
    /**
     * 安装插件时回调
     * @return void
     */
    function onInstall();

    /**
     * 卸载插件时回调
     * @return void
     */
    function onUninstall();

    /**
     * 收到请求时回调
     * @param string $path
     * @return Router|null
     */
    function onRequest(string $path);

    /**
     * 添加文章时回调
     * @param ArticleModel $article
     * @param BaseController $controller
     * @return bool 返回false阻止数据写入到数据库
     */
    function onInsertArticle(ArticleModel $article, BaseController $controller): bool;

    /**
     * 修改文章时回调
     * @param ArticleModel $new 修改后的新值
     * @param ArticleModel $old 老值
     * @param BaseController $controller
     * @return bool 返回false阻止数据写入到数据库
     */
    function onUpdateArticle(ArticleModel $new, ArticleModel $old, BaseController $controller): bool;

    /**
     * 渲染文章页时回调
     * @param ArticleModel $article 要渲染的文章
     * @return TemplateHandler
     */
    function onRenderArticleTemplate(ArticleModel $article): TemplateHandler;

    /**
     * 出现异常时回调
     * @param \Throwable $e
     * @return mixed
     */
    function onException(\Throwable $e);
}