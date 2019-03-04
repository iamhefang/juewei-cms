<?php

namespace link\hefang\site\content\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\content\models\ArticleModel;
use link\hefang\site\content\models\ViewArticleModel;
use link\hefang\site\users\models\LoginModel;


class ArticleController extends BaseController
{
    public function insert(): BaseView
    {
        $login = $this->_checkAdmin();
        $alias = $this->_post("alias");
        $title = $this->_post("title");
        $keywords = $this->_post("keywords");
        $description = $this->_post("description");
        $html = $this->_post("html");
        $markdown = $this->_post("markdown");
        $catalog = $this->_post("catalog");
        $password = $this->_post("password");
        $cateId = $this->_post("cateId");
        $isDraft = ParseHelper::parseBoolean($this->_post("isDraft", 'true'), true);
        $type = $this->_post("type", 'article');
        $covers = $this->_post("covers");
        $tags = $this->_post("tags");

        try {
            $m = new ArticleModel();
            $m->setId(RandomHelper::guid())
                ->setAlias($alias)
                ->setTitle($title)
                ->setKeywords($keywords)
                ->setDescription($description)
                ->setHtml($html)
                ->setMarkdown($markdown)
                ->setCatalog($catalog)
                ->setPassword($password)
                ->setPostTime(TimeHelper::currentTimeMillis())
                ->setLastAlterTime(TimeHelper::currentTimeMillis())
                ->setCateId($cateId)
                ->setIsDraft($isDraft)
                ->setType($type)
                ->setCovers(is_array($covers) ? $covers : [])
                ->setAuthorId($login->getId());
            $res = ArticleModel::add($m, $tags);
            return $res ? $this->_apiSuccess() : $this->_apiFailed('添加文章失败');
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("新建文章异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function update(): BaseView
    {
        $login = $this->_checkAdmin();
        $id = $this->_request('id');
        $alias = $this->_post("alias");
        $title = $this->_post("title");
        $keywords = $this->_post("keywords");
        $description = $this->_post("description");
        $html = $this->_post("html");
        $markdown = $this->_post("markdown");
        $catalog = $this->_post("catalog");
        $password = $this->_post("password");
        $cateId = $this->_post("cateId");
        $isDraft = ParseHelper::parseBoolean($this->_post("isDraft", 'true'), true);
        $covers = $this->_post("covers");
        $tags = $this->_post("tags");

        try {
            if (StringHelper::isNullOrBlank($id) || strlen($id) !== 40) {
                return $this->_apiFailed('参数异常');
            }
            $m = ArticleModel::get($id);
            if (!($m instanceof ArticleModel) || !$m->isEnable()) {
                return $this->_apiFailed('该文章不存在或已被删除');
            }
            $m->setAlias($alias)
                ->setTitle($title)
                ->setKeywords($keywords)
                ->setDescription($description)
                ->setHtml($html)
                ->setMarkdown($markdown)
                ->setCatalog($catalog)
                ->setPassword($password)
                ->setCateId($cateId)
                ->setIsDraft($isDraft)
                ->setCovers(is_array($covers) ? $covers : []);
            $res = ArticleModel::alter($m, is_array($tags) ? $tags : []);
            return $res ? $this->_apiSuccess() : $this->_apiFailed('更新文章异常');
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("更新文章异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function draft(): baseview
    {
        $this->_checkAdmin();
        try {
            $id = $this->_request('id');
            $isDraft = ParseHelper::parseBoolean($this->_post("isDraft", 'true'), true);
            if (StringHelper::isNullOrBlank($id) || strlen($id) !== 40) {
                return $this->_apiFailed('参数异常');
            }
            $m = ArticleModel::get($id);
            if (!($m instanceof ArticleModel) || !$m->isEnable()) {
                return $this->_apiFailed('该文章不存在或已被删除');
            }
            $res = $m->setIsDraft($isDraft)->update(['is_draft']);
            return $res ? $this->_apiSuccess() : $this->_apiFailed('更新文章状态失败');
        } catch (\Throwable $e) {
            return $this->_exception($e, null, "更新文章状态异常");
        }
    }

    public function list(): BaseView
    {
        $login = $this->_getLogin();
        try {
            $search = $this->_request("search");
            $timeStart = $this->_request("timeStart");
            $timeEnd = $this->_request("timeEnd");
            $tag = $this->_request("tag");
            $where = '`enable` = TRUE';

            if (($login instanceof LoginModel) && $login->isAdmin()) {
                $type = $this->_request("type");
                $isDraft = $this->_request("isDraft");
                if ($isDraft === '1' || $isDraft === '0') {
                    $where .= " AND `is_draft` = '{$isDraft}'";
                }
                if ($type === 'article' || $type === 'page') {
                    $where .= " AND `type` = '{$type}'";
                }
            } else {
                $where .= " AND `is_draft` = FALSE";
            }

            if (!StringHelper::isNullOrBlank($timeStart)) {
                $where .= " AND `post_time` >= '{$timeStart}'";
            }

            if (!StringHelper::isNullOrBlank($timeEnd)) {
                $where .= " AND `post_time` <= '{$timeEnd}'";
            }

            if (!StringHelper::isNullOrBlank($tag)) {
                $tag = addslashes($tag);
                $where .= " AND `id` IN (SELECT `content_id` FROM `tag` WHERE `tag` = '{$tag}')";
            }

            $pager = ViewArticleModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                $search, $where,
                [$this->_sort()]
            );

            return $this->_apiSuccess($pager);
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("文章列表异常", $e->getMessage(), $e);
            return $this->_apiFailed($e->getMessage());
        }
    }

    public function up(): BaseView
    {
        $id = $this->_request("id");
        if (strlen($id) != 40) {
            return $this->_apiFailed('参数异常');
        }
        ArticleModel::up($id);
        return $this->_apiSuccess();
    }

    public function get(string $id = null): BaseView
    {
        $login = $this->_getLogin();
        $id = $this->_request('id', $id);
        $password = $this->_request('password');
        $id = addslashes($id);
        $where = "`enable` = TRUE AND `id` = '{$id}'";
        if (!($login instanceof LoginModel) || !$login->isAdmin()) {
            $where .= " AND `is_draft` = FALSE";
        }
        try {
            $m = ViewArticleModel::find($where);
            if ($m instanceof ViewArticleModel) {
                //文章有密码
                if (!StringHelper::isNullOrBlank($m->getPassword())) {
                    //当前没有用户登录或登录的人不是管理员
                    if ((!($login instanceof LoginModel) || !$login->isAdmin())) {
                        if (StringHelper::isNullOrBlank($password)) {
                            return $this->_needPassword('该文章已加密， 请输入密码');
                        }
                        if ($m->getPassword() !== $password) {
                            return $this->_needPassword('密码错误， 请重新输入密码');
                        }
                    }
                }
                return $this->_apiSuccess($m);
            } else {
                return $this->_apiFailed("获取文章失败");
            }
        } catch (\Throwable $e) {
            return $this->_exception($e, null, '文章详情异常');
        }
    }
}