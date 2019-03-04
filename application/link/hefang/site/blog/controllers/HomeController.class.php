<?php

namespace link\hefang\site\blog\controllers;
defined('PROJECT_NAME') or die("Access Refused");

use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\site\content\models\SwiperModel;
use link\hefang\site\content\models\ViewArticleModel;
use link\hefang\site\content\models\ViewTagModel;
use link\hefang\site\statistics\models\LogVisitModel;
use link\hefang\site\users\models\LoginModel;

include PATH_APPLICATION . DS . 'link' . DS . 'hefang' . DS . 'site' . DS . 'view_functions.php';

class HomeController extends BaseController
{
    public function index(): BaseView
    {
        $this->log();
        $login = $this->_getLogin();
        $where = "`enable` = TRUE AND `type` = 'article'";
        if (!($login instanceof LoginModel) || !$login->isAdmin()) {
            $where .= " AND `is_draft` = FALSE";
        }
        try {
            $pager = ViewArticleModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(10),
                null,
                $where,
                [new SqlSort('last_alter_time', SqlSort::TYPE_ASC), new SqlSort('read_count')]
            );

            $swipers = [];

            $swiperEnable = Mvc::getConfig('swiper|enable', false);

            if ($swiperEnable) {
                $swiperPager = SwiperModel::pager(1, 10, null,
                    "`enable` = true",
                    [new SqlSort('sort', SqlSort::TYPE_DESC)]
                );
                foreach ($swiperPager->getData() as $swiper) {
                    if (!($swiper instanceof SwiperModel) || !$swiper->isExist()) continue;
                    $swipers[] = $swiper->getContent();
                }
            }

            return $this->_template($this->makeData([
                'pager' => $pager,
                'title' => '首页',
                'swipers' => $swipers,
                'swiperEnable' => $swiperEnable && !empty($swipers)
            ]));
        } catch (SqlException $e) {
            return $this->_exception($e);
        }

    }

    public function tools(): BaseView
    {
        return $this->_template($this->makeData([
            'title' => '工具'
        ]));
    }

    public function tag(string $tag): BaseView
    {
        $this->log();
        try {
            $tag = addslashes($tag);
            $login = $this->_getLogin();
            $where = "`enable` = TRUE AND `id` IN (SELECT content_id FROM `tag` WHERE `type` = 'article' AND `tag` = '{$tag}')";
            if (!($login instanceof LoginModel) || !$login->isAdmin()) {
                $where .= " AND `is_draft` = FALSE";
            }
            $pager = ViewArticleModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                null, $where,
                [$this->_sort()]
            );
            return $this->_template($this->makeData([
                'pager' => $pager,
                'title' => "'{$tag}'相关的文章",
                'highlight' => null
            ]), 'list');
        } catch (SqlException $e) {
            return $this->_exception($e);
        }
    }

    public function search(): BaseView
    {
        $this->log();
        $search = $this->_request("search", '');
        $search = trim($search);
        $search = htmlentities($search);

        if (StringHelper::isNullOrBlank($search)) {
            return $this->_exception(null, '请输入搜索内容');
        }
        $login = $this->_getLogin();
        $where = "`enable` = TRUE";
        if (!($login instanceof LoginModel) || !$login->isAdmin()) {
            $where .= " AND `is_draft` = FALSE";
        }
        try {
            $pager = ViewArticleModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                addslashes($search), $where,
                [$this->_sort()]
            );
            if ($pager->getTotal() > 5) {
                $hotSearch = Mvc::getCache()->get('hotSearch', []);
                isset($hotSearch[$search]) ? ($hotSearch[$search] += 1) : ($hotSearch[$search] = 1);
                $hotSearch = array_filter($hotSearch, function ($count, $item) {
                    return $count >= 5;
                });
                asort($hotSearch);
                Mvc::getCache()->set('hotSearch', $hotSearch);
            }
            return $this->_template($this->makeData([
                'pager' => $pager,
                'title' => "{$search} 的搜索结果",
                'highlight' => $search
            ]), 'list');
        } catch (\Throwable $e) {
            return $this->_exception($e);
        }
    }

    public function article(string $idOrAlias, string $type = 'article'): BaseView
    {
        $this->log();
        $login = $this->_getLogin();
        if (StringHelper::isNullOrBlank($idOrAlias)) {
            return $this->_404();
        }
        $idOrAlias = addslashes($idOrAlias);
        try {
            $m = Mvc::getCache()->get($idOrAlias);
            if (!($m instanceof ViewArticleModel) || Mvc::isDebug()) {
                $m = ViewArticleModel::find("(`id` = '{$idOrAlias}' OR `alias` = '{$idOrAlias}') AND `type` = '{$type}'");
                if (!($m instanceof ViewArticleModel)
                    || !$m->isExist()
                    || !$m->isEnable()) {
                    return $this->_404();
                }
            }
            $needPassword = false;
            $needPasswordMessage = '';
            if ($m->isDraft()) {
                if (!$login
                    || (($login instanceof LoginModel)
                        && !$login->isAdmin()
                        && $m->getAuthorId() !== $login->getId())) {
                    return $this->_404();
                }
            } else if (!StringHelper::isNullOrBlank($m->getPassword())) {
                $password = $this->_post('password');
                if (StringHelper::isNullOrBlank($password)) {
                    $needPasswordMessage = '该文章已加密, 请输入密码';
                } elseif ($password !== $m->getPassword()) {
                    $needPasswordMessage = '密码输入错误, 请重新输入';
                }
                $needPassword = $password !== $m->getPassword();
            }
            if (!($login instanceof LoginModel) || !$login->isAdmin()) {
                $m->addRead();
            }
            Mvc::getCache()->set($idOrAlias, $m);
            return $this->_template($this->makeData([
                'article' => $m,
                'title' => $m->getTitle(),
                'keywords' => $m->getKeywords(),
                'description' => $m->getDescription(),
                'commentEnable' => Mvc::getConfig('comment|enable', true),
                'needPassword' => $needPassword,
                'needPasswordMessage' => $needPasswordMessage
            ]));
        } catch (\Throwable $e) {
            return $this->_exception($e);
        }
    }

    public function page(string $idOrAlias): BaseView
    {
        return $this->article($idOrAlias, __FUNCTION__);
    }

    public function date(string $params): BaseView
    {
        $this->log();
        $date = json_decode($params, true);
        try {
            $title = $date['year'] . '年';
            $search = $this->_request("search");

            if ($date['month']) {
                $title .= $date['month'] . '月';
            }

            if ($date['day']) {
                $title .= $date['day'] . '日';
            }

            $timeStart = $date['year'] . '-' . ($date['month'] ?: 1) . '-' . ($date['day'] ?: 1) . ' 00:00:00';
            $timeEnd = $date['year'] . '-' . ($date['month'] ?: 12) . '-' . ($date['day'] ?: TimeHelper::daysOf($date['year'], $date['month'] ?: 12)) . ' 23:59:59';

            $where = "`enable` = TRUE AND `type` = 'article' AND `post_time` >= '{$timeStart}' AND `post_time` <= '{$timeEnd}'";

            $pager = ViewArticleModel::pager(
                $this->_pageIndex(),
                $this->_pageSize(),
                addslashes($search),
                $where,
                [$this->_sort()]
            );
            return $this->_template($this->makeData([
                'pager' => $pager,
                'title' => $title . '的文章',
                'highlight' => null
            ]), 'list');
        } catch (\Throwable $e) {
            return $this->_exception($e);
        }
    }

    public function _404(): BaseView
    {
        return $this->_template($this->makeData([
            'title' => '404 内容不存在'
        ]), '404');
    }


    public function _exception(\Throwable $e = null, string $message = null, string $title = null): BaseView
    {
        Mvc::getLogger()->error("出现异常: $message", $e ? $e->getMessage() : '', $e);
        return $this->_template($this->makeData([
            'title' => $title ?: '出现错误',
            'message' => $message ?: ($e ? $e->getMessage() : '')
        ]), 'error');
    }


    private function makeData(array $array = null): array
    {
        $tags = [];
        try {
            $tags = ViewTagModel::pager(
                1, 300, null, "`type` = 'article'"
            )->getData();
        } catch (\Throwable $e) {

        }

        $hotSearch = array_keys(Mvc::getCache()->get('hotSearch', []));

        return array_merge([
            'urlPrefix' => '',
            'title' => '',
            'name' => Mvc::getConfig("site|name", '何方博客'),
            'keywords' => Mvc::getConfig("site|keywords", ''),
            'description' => Mvc::getConfig("site|description", ''),
            'icp' => Mvc::getConfig('site|icp', ''),
            'login' => $this->_getLogin(),
            'tags' => $tags,
            'hotSearch' => $hotSearch,
            'highlight' => null,
            'search' => $this->_request("search")
        ], $array ?: []);
    }

    private function log()
    {
        $login = $this->_getLogin();
        if (($login instanceof LoginModel) && $login->isAdmin()) return;
        if ($this->_cookie('visit')) {
            return;
        }
        setcookie('visit', time());
        $m = new LogVisitModel();
        try {
            $m->setId(RandomHelper::guid())
                ->setIp($this->_ip())
                ->setUserAgent($this->_userAgent())
                ->setReferer($this->_header("referer"))
                ->setUrl($_SERVER['REQUEST_URI'])
                ->setVisitTime(TimeHelper::formatMillis())
                ->insert();
        } catch (\Throwable $e) {
            Mvc::getLogger()->error("日志记录异常", $e->getMessage(), $e);
        }
    }
}